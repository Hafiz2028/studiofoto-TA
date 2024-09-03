<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\ConstGuards;
use App\Helpers\ConstDefaults;
use App\Models\User;
use App\Models\Customer;
use App\Models\Venue;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;


class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return redirect()->route('admin.login')->with('fail', 'Akun ini bukan pengguna Admin.');
        }
        $venue = Venue::where('status', 0)->get();
        $totalVenues = Venue::count();
        $activeVenues = Venue::where('status', 1)->count();
        $pendingVenues = Venue::where('status', 0)->count();
        $rejectedVenues = Venue::where('status', 2)->count();
        $totalOwner = Owner::count();
        $totalCust = Customer::count();
        $activeOwner = Owner::whereHas('venues')->count();
        $data = [
            'pageTitle' => "Admin List",
            'user' => $user,
            'totalVenues' => $totalVenues,
            'activeVenues' => $activeVenues,
            'pendingVenues' => $pendingVenues,
            'rejectedVenues' => $rejectedVenues,
            'activeOwner' => $activeOwner,
            'totalOwner' => $totalOwner,
            'totalCust' => $totalCust,
            'venue' => $venue,
        ];

        return view('back.pages.admin.home', $data);
    }
    //login start
    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:users,email,role,admin',
                'password' => 'required|min:5|max:45'
            ], [
                'login_id.required' => 'Masukkan Username atau Email',
                'login_id.email' => 'Email salah',
                'login_id.exists' => 'Tidak ada Email ini',
                'password.required' => 'Masukkan Password'
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:users,username,role,admin',
                'password' => 'required|min:5|max:45'
            ], [
                'login_id.required' => 'Masukkan Username atau Email',
                'login_id.exists' => 'Tidak ada Username ini',
                'password.required' => 'Masukkan Password'
            ]);
        }
        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password,
            'role' => 'admin',

        );
        if (Auth::attempt($creds)) {
            $user = Auth::user();
            if ($user->role == 'admin') {
                return redirect()->route('admin.home')->with('success', "Welcome to Admin's Home Page");
            } else {
                return redirect()->back()->with('fail', 'User yang login bukan Admin, silahkan coba lagi');
            }
        } else {
            session()->flash('fail', 'Password salah');
            return redirect()->route('admin.login');
        }
    }
    //login end

    //logout start
    public function logoutHandler(Request $request)
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('fail', 'akun anda telah Log out dari sistem.');
    }

    //logout end

    //send email reset password start
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => ':attribute harus diisi',
            'email.email' => 'Email Salah',
            'email.exists' => ':attribute Tidak terdaftar'
        ]);

        //Get admin details
        $user = User::where('email', $request->email)->first();
        if ($user && $user->role === 'admin') {
            //generate token
            $token = base64_encode(Str::random(64));

            //check if there is an existing reset password token
            $oldToken = DB::table('password_reset_tokens')
                ->where(['email' => $request->email, 'guard' => 'admin'])
                ->first();

            if ($oldToken) {
                //update token
                DB::table('password_reset_tokens')
                    ->where(['email' => $request->email, 'guard' => 'admin'])
                    ->update([
                        'token' => $token,
                        'created_at' => Carbon::now()
                    ]);
            } else {
                DB::table('password_reset_tokens')->insert([
                    'email' => $request->email,
                    'guard' => 'admin',
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
            }

            $actionLink = route('admin.reset-password', ['token' => $token, 'email' => $request->email]);

            $data = array(
                'actionLink' => $actionLink,
                'user' => $user
            );

            $mail_body = view('email-templates.admin-forgot-email-template', $data)->render();

            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $user->email,
                'mail_recipient_name' => $user->name,
                'mail_subject' => 'Reset password',
                'mail_body' => $mail_body,
            );

            if (sendEmail($mailConfig)) {
                session()->flash('success', 'We have e-mailed your password reset link.');
                return redirect()->route('admin.forgot-password');
            } else {
                session()->flash('fail', 'Something went wrong!');
                return redirect()->route('admin.forgot-password');
            }
        } else {
            return redirect()->route('admin.forgot-password')->with('fail', 'Invalid Admin email.');
        }
    }
    //send email reset password link

    //reset password & email start
    public function resetPassword(Request $request, $token = null)
    {
        $check_token = DB::table('password_reset_tokens')
            ->where(['token' => $token, 'guard' => 'admin'])
            ->first();
        if ($check_token) {
            //check if token is not expired
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $check_token->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > ConstDefaults::tokenExpiredMinutes) {
                //if token is expired
                session()->flash('fail', 'Token expired, request another reset password link.');
                return redirect()->route('admin.forgot-password', ['token' => $token]);
            } else {
                return view('back.pages.admin.auth.reset-password')->with(['token' => $token]);
            }
        } else {
            session()->flash('fail', 'Invalid token!, request another reset password link');
            return redirect()->route('admin.forgot-password', ['token' => $token]);
        }
    }

    public function resetPasswordHandler(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:5|max:45|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation' => 'required'
        ]);

        $token = DB::table('password_reset_tokens')
            ->where(['token' => $request->token, 'guard' => 'admin'])
            ->first();
        if (!$token) {
            return redirect()->route('admin.reset-password')->with('fail', 'Reset Password telah kadaluarsa, silahkan reset ulang kembali.');
        }
        // GET admin details
        $user = User::where('email', $token->email)->first();
        //update admin password
        if ($user && $user->role === 'admin') {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            //delete token record
            DB::table('password_reset_tokens')->where([
                'email' => $user->email,
                'token' => $request->token,
                'guard' => 'admin'
            ])->delete();

            //send email to notify admin
            $data = array(
                'user' => $user,
                'new_password' => $request->new_password
            );

            $mail_body = view('email-templates.admin-reset-email-template', $data)->render();

            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $user->email,
                'mail_recipient_name' => $user->name,
                'mail_subject' => 'Password Changed',
                'mail_body' => $mail_body
            );

            sendEmail($mailConfig);
            return redirect()->route('admin.login')->with('success', 'Done!, Your password has been changed, Use new password to login.');
        } else {
            return redirect()->route('admin.reset-password')->with('fail', 'Invalid admin email.');
        }
    }
    //reset password & email end

    //profile page start
    public function profileView(Request $request)
    {
        $admin = null;
        if (Auth::check()) {
            $userId = Auth::id();
            $user = User::where('id', $userId)
                ->where('role', 'admin')
                ->first();
        }
        return view('back.pages.admin.profile', compact('user'));
    }

    public function changeProfilePicture(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            $path = 'images/users/admins/';
            $file = $request->file('adminProfilePictureFile');
            $old_picture = $user->picture;
            $file_path = $path . $old_picture;
            $filename = 'ADMIN_IMG_' . rand(2, 1000) . $user->id . time() . uniqid() . '.jpg';
            $upload = $file->move(public_path($path), $filename);

            if ($upload) {
                if ($old_picture != null && File::exists(public_path($file_path))) {
                    File::delete(public_path($file_path));
                }
                $user->update(['picture' => $filename]);
                return response()->json(['status' => 1, 'msg' => 'Your profile picture has been successfully uploaded']);
            } else {
                return response()->json(['status' => 0, 'msg' => 'Something went wrong.']);
            }
        } else {
            return response()->json(['status' => 0, 'msg' => 'Unauthorized access.']);
        }
    }
    //profile page end

    //crud list admin user
    public function adminList(Request $request)
    {
        $currentAdmin = Auth::user();
        if (!$currentAdmin || $currentAdmin->role !== 'admin') {
            return redirect()->route('admin.login');
        }
        $admins = User::where('id', '!=', $currentAdmin->id)
            ->where('role', 'admin')
            ->get();
        $data = [
            'pageTitle' => "Admin List",
            'admins' => $admins
        ];

        return view('back.pages.admin.manage-users.admin.admin-list', $data);
    }
    public function addAdmin(Request $request)
    {
        $data = [
            'pageTitle' => "Add Admin"
        ];

        return view('back.pages.admin.manage-users.admin.add-admin', $data);
    }

    public function storeAdmin(Request $request)
    {
        //validate
        $validatedData = $request->validate([
            'name' => 'required|min:5|unique:users,name',
            'username' => 'required|min:5|unique:users,username|regex:/^\S*$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:45',
            'handphone' => 'required|min:9|max:15',
            'address' => 'required|string|max:255',
            'password_confirmation' => 'required|min:5|max:45|same:password',
        ], [
            'name.required' => ':Attribute is required.',
            'name.min' => ':Attribute must be at least 5 characters.',
            'name.unique' => ':Attribute has already been taken.',
            'username.regex' => ':Attribute may only contain letters, numbers, and underscores.',
            'username.max' => ':Attribute must not exceed 255 characters.',
            'username.required' => ':Attribute is required.',
            'username.min' => ':Attribute must be at least 5 characters.',
            'username.unique' => ':Attribute has already been taken.',
            'email.required' => ':Attribute is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => ':Attribute has already been taken.',
            'password.required' => ':Attribute is required.',
            'password.min' => ':Attribute must be at least 5 characters.',
            'password.max' => ':Attribute may not be greater than 45 characters.',
            'handphone.required' => ':Attribute field is required.',
            'password_confirmation.required' => ':Attribute is required.',
            'password_confirmation.min' => ':Attribute must be at least 5 characters.',
            'password_confirmation.max' => ':Attribute may not be greater than 45 characters.',
            'handphone.min' => ':Attribute must be at least 9 characters.',
            'handphone.max' => ':Attribute may not be greater than 15 characters.',
            'address.required' => ':Attribute is required.',
            'address.string' => ':Attribute must be a string.',
            'address.max' => ':Attribute may not be greater than 255 characters.',
        ]);
        $admin = new User();

        $admin->name = $validatedData['name'];
        $admin->role = 'admin';
        $admin->username = $validatedData['username'];
        $admin->email = $validatedData['email'];
        $admin->password = Hash::make($validatedData['password']);
        $admin->handphone = $validatedData['handphone'];
        $admin->address = $validatedData['address'];
        $saved = $admin->save();

        if ($saved) {
            return redirect()->route('admin.user.adminList')->with('success', '<b>' . ucfirst($validatedData['name']) . '</b> user has been added');
        } else {
            return redirect()->route('admin.user.add-admin')->with('fail', 'something went wrong, try again');
        }
    }
    public function editAdmin(Request $request)
    {
        $currentAdmin = Auth::user();
        if (!$currentAdmin || $currentAdmin->role !== 'admin') {
            return redirect()->route('admin.login');
        }
        $admin_id = $request->id;
        $admin = User::findOrFail($admin_id);
        $data = [
            'pageTitle' => 'Edit Admin User',
            'admin' => $admin
        ];
        return view('back.pages.admin.manage-users.admin.edit-admin', $data);
    }
    public function updateAdmin(Request $request)
    {
        $currentAdmin = Auth::user();
        if (!$currentAdmin || $currentAdmin->role !== 'admin') {
            return redirect()->route('admin.login');
        }
        $admin_id = $request->admin_id;
        $admin = User::findOrFail($admin_id);

        //validate
        $request->validate([
            'name' => 'required|min:5|unique:users,name,' . $admin_id,
            'username' => 'required|min:5|unique:users,username,' . $admin_id . '|regex:/^\S*$/',
            'handphone' => 'required|min:9|max:15',
            'address' => 'required|string|max:255',
        ], [
            'name.required' => ':Attribute is required.',
            'name.min' => ':Attribute must be at least 5 characters.',
            'name.unique' => ':Attribute has already been taken.',
            'username.regex' => ':Attribute may only contain letters, numbers, and underscores.',
            'username.required' => ':Attribute is required.',
            'username.min' => ':Attribute must be at least 5 characters.',
            'username.unique' => ':Attribute has already been taken.',
            'handphone.required' => ':Attribute field is required.',
            'handphone.required' => ':Attribute field is required.',
            'handphone.min' => ':Attribute must be at least 9 characters.',
            'handphone.max' => ':Attribute may not be greater than 15 characters.',
            'address.required' => ':Attribute is required.',
            'address.string' => ':Attribute must be a string.',
            'address.max' => ':Attribute may not be greater than 255 characters.',
        ]);
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->handphone = $request->handphone;
        $admin->address = $request->address;
        $saved = $admin->save();
        if ($saved) {
            return redirect()->route('admin.user.adminList', ['id' => $admin_id])->with('success', '<b>' . ucfirst($request->name) . '</b> service has been updated');
        } else {
            return redirect()->route('admin.user.edit-admin', ['id' => $admin_id])->with('fail', 'Something went wrong, try again');
        }
    }

    public function deleteAdmin(Request $request)
    {
        $currentAdmin = Auth::user();
        if (!$currentAdmin || $currentAdmin->role !== 'admin') {
            return redirect()->route('admin.login');
        }
        $admin_id = $request->id;
        $admin = User::findOrFail($admin_id);
        $admin_name = $admin->name;

        $delete = $admin->delete();
        if ($delete) {
            return redirect()->route('admin.user.adminList')->with('success', 'User <b>' . ucfirst($admin_name) . '</b> deleted successfully');
        } else {
            return redirect()->route('admin.user.adminList')->with('fail', "User <b>" . ucfirst($admin_name) . "</b> can't deleted, Try again");
        }
    }
}
