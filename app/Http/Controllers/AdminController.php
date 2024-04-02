<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use constGuards;
use constDefaults;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;


class AdminController extends Controller
{
    //login start
    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:admins,email',
                'password' => 'required|min:5|max:45'
            ], [
                'login_id,required' => 'Email or Username is required',
                'login_id.email' => 'Invalid email address',
                'login_id.exists' => 'Email is not exists in system',
                'password.required' => 'Password is required'
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:admins,username',
                'password' => 'required|min:5|max:45'
            ], [
                'login_id.required' => 'Email or Username is required',
                'login_id.exists' => 'Username is not exists in system',
                'password.required' => 'Password is required'
            ]);
        }
        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password

        );
        if (Auth::guard('admin')->attempt($creds)) {
            return redirect()->route('admin.home');
        } else {
            session()->flash('fail', 'wrong password');
            return redirect()->route('admin.login');
        }
    }
    //login end

    //logout start
    public function logoutHandler(Request $request)
    {
        Auth::guard('admin')->logout();
        session()->flash('fail', 'You are logged out');
        return redirect()->route('admin.login');
    }
    //logout end

    //send email reset password start
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email'
        ], [
            'email.required' => 'The :attribute is required',
            'email.email' => 'Invalid email address',
            'email.exists' => 'The :attribute is not exists in system'
        ]);

        //Get admin details
        $admin = Admin::where('email', $request->email)->first();

        //generate token
        $token = base64_encode(Str::random(64));

        //check if there is an existing reset password token
        $oldToken = DB::table('password_reset_tokens')
            ->where(['email' => $request->email, 'guard' => constGuards::ADMIN])
            ->first();

        if ($oldToken) {
            //update token
            DB::table('password_reset_tokens')
                ->where(['email' => $request->email, 'guard' => constGuards::ADMIN])
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        } else {
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'guard' => constGuards::ADMIN,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }

        $actionLink = route('admin.reset-password', ['token' => $token, 'email' => $request->email]);

        $data = array(
            'actionLink' => $actionLink,
            'admin' => $admin
        );

        $mail_body = view('email-templates.admin-forgot-email-template', $data)->render();

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $admin->email,
            'mail_recipient_name' => $admin->name,
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
    }
    //send email reset password link

    //reset password & email start
    public function resetPassword(Request $request, $token = null)
    {
        $check_token = DB::table('password_reset_tokens')
            ->where(['token' => $token, 'guard' => constGuards::ADMIN])
            ->first();
        if ($check_token) {
            //check if token is not expired
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $check_token->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > constDefaults::tokenExpiredMinutes) {
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
            ->where(['token' => $request->token, 'guard' => constGuards::ADMIN])
            ->first();

        // GET admin details
        $admin = Admin::where('email', $token->email)->first();

        //update admin password
        Admin::where('email', $admin->email)->update([
            'password' => Hash::make($request->new_password)
        ]);

        //delete token record
        DB::table('password_reset_tokens')->where([
            'email' => $admin->email,
            'token' => $request->token,
            'guard' => constGuards::ADMIN
        ])->delete();

        //send email to notify admin
        $data = array(
            'admin' => $admin,
            'new_password' => $request->new_password
        );

        $mail_body = view('email-templates.admin-reset-email-template', $data)->render();

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $admin->email,
            'mail_recipient_name' => $admin->name,
            'mail_subject' => 'Password Changed',
            'mail_body' => $mail_body
        );

        sendEmail($mailConfig);
        return redirect()->route('admin.login')->with('success', 'Done!, Your password has been changed, Use new password to login.');
    }
    //reset password & email end

    //profile page start
    public function profileView(Request $request)
    {
        $admin = null;
        if (Auth::guard('admin')->check()) {
            $admin = Admin::findOrFail(auth()->id());
        }
        return view('back.pages.admin.profile', compact('admin'));
    }

    public function changeProfilePicture(Request $request)
    {
        $admin = Admin::findOrFail(auth('admin')->id());
        $path = 'images/users/admins/';
        $file = $request->file('adminProfilePictureFile');
        $old_picture = $admin->getAttributes()['picture'];
        $file_path = $path . $old_picture;
        $filename = 'ADMIN_IMG_' . rand(2, 1000) . $admin->id . time() . uniqid() . '.jpg';

        $upload = $file->move(public_path($path), $filename);

        if ($upload) {
            if ($old_picture != null && File::exists(public_path($file_path))) {
                File::delete(public_path($file_path));
            }
            $admin->update(['picture' => $filename]);
            return response()->json(['status' => 1, 'msg' => 'Your profile picture has been successfully uploaded']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong.']);
        }
    }
    //profile page end

    //crud list admin user
    public function adminList(Request $request)
    {
        $data = [
            'pageTitle' => "Admin List"
        ];

        return view('back.pages.admin.manage-users.admin.admin-list', [
            $data,
            'admins' => Admin::all()
        ]);
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
            'name' => 'required|min:5|unique:admins,name',
            'username' => 'required|min:5|unique:admins,username|regex:/^\S*$/',
            'email' => 'required|email|unique:admins,email',
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
            'handphone.required' => ':Attribute field is required.',
            'handphone.min' => ':Attribute must be at least 9 characters.',
            'handphone.max' => ':Attribute may not be greater than 15 characters.',
            'address.required' => ':Attribute is required.',
            'address.string' => ':Attribute must be a string.',
            'address.max' => ':Attribute may not be greater than 255 characters.',
        ]);
        $admin = new Admin();

        $admin->name = $validatedData['name'];
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
        $admin_id = $request->id;
        $admin = Admin::findOrFail($admin_id);
        $data = [
            'pageTitle' => 'Edit Admin User',
            'admin' => $admin
        ];
        return view('back.pages.admin.manage-users.admin.edit-admin', $data);
    }
    public function updateAdmin(Request $request)
    {
        $admin_id = $request->admin_id;
        $admin = Admin::findOrFail($admin_id);

        //validate
        $request->validate([
            'name' => 'required|min:5|unique:admins,name,' . $admin_id,
            'username' => 'required|min:5|unique:admins,username,' . $admin_id . '|regex:/^\S*$/',
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
        $admin_id = $request->id;
        $admin = Admin::findOrFail($admin_id);
        $admin_name = $admin->name;

        $delete = $admin->delete();
        if ($delete) {
            return redirect()->route('admin.user.adminList')->with('success', 'User <b>' . ucfirst($admin_name) . '</b> deleted successfully');
        } else {
            return redirect()->route('admin.user.adminList')->with('fail', "User <b>" . ucfirst($admin_name) . "</b> can't deleted, Try again");
        }
    }
}
