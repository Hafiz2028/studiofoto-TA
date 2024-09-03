<?php

namespace App\Http\Controllers;

use App\Helpers\ConstDefaults;

use App\Models\Owner;
use App\Models\Rent;
use App\Models\ServiceEvent;
use App\Models\ServicePackage;
use App\Models\ServicePackageDetail;
use App\Models\User;
use App\Models\Venue;
use App\Models\VerificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OwnerController extends Controller
{
    public $now;

    public function __construct()
    {
        // Mendapatkan waktu saat ini dengan zona waktu 'Asia/Jakarta'
        $this->now = Carbon::now()->tz('Asia/Jakarta');
    }
    //auth
    public function login(Request $request)
    {
        $data = [
            'pageTitle' => 'Login Owner',
        ];
        return view('back.pages.owner.auth.login', $data);
    }
    public function register(Request $request)
    {
        $data = [
            'pageTitle' => 'Register Owner',
        ];
        return view('back.pages.owner.auth.register', $data);
    }
    public function home(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'owner') {
            return redirect()->route('owner.login')->with('fail', 'You must login first as an owner.');
        }
        $owner = $user->owner;
        $venues = $owner->venues()->get(['id', 'status']);
        $venueIds = $venues->pluck('id');
        $rents = Rent::whereHas('servicePackageDetail.servicePackage.serviceEvent.venue', function ($query) use ($venueIds) {
            $query->whereIn('id', $venueIds);
        })->get(['id', 'rent_status']);

        $data = [
            'pageTitle' => 'Owner Home Page',
            'venues' => $venues,
            'rents' => $rents,
            'owner' => $owner,
            'user' => $user
        ];
        return view('back.pages.owner.home', $data);
    }
    public function getRentEvents($ownerId)
    {
        // Mengambil data rents dan rent_details
        $venueIds = Venue::where('owner_id', $ownerId)->pluck('id');
        $serviceEventIds = ServiceEvent::whereIn('venue_id', $venueIds)->pluck('id');
        $servicePackageIds = ServicePackage::whereIn('service_event_id', $serviceEventIds)->pluck('id');
        $servicePackageDetailIds = ServicePackageDetail::whereIn('service_package_id', $servicePackageIds)->pluck('id');
        $rentEvents = Rent::with([
            'servicePackageDetail.servicePackage.serviceEvent.venue',
            'rentDetails.openingHour.hour'
        ])
            ->whereIn('service_package_detail_id', $servicePackageDetailIds)
            ->get();

        // Mengirimkan data dalam format JSON
        return response()->json($rentEvents);
    }
    public function createOwner(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'handphone' => 'required|numeric|min:8',
            'password' => 'min:5|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:5',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->handphone = $request->handphone;
        $user->password = Hash::make($request->password);
        $user->role = 'owner';
        $saved = $user->save();
        if ($saved) {
            $owner = new Owner();
            $owner->user_id = $user->id;
            $owner->verified = 0;
            $owner->save();
            $token = base64_encode(Str::random(64));
            VerificationToken::create([
                'user_type' => 'owner',
                'email' => $request->email,
                'token' => $token
            ]);

            $actionLink = route('owner.verify', ['token' => $token]);
            $data['action_link'] = $actionLink;
            $data['owner_name'] = $request->name;
            $data['owner_username'] = $request->username;
            $data['owner_email'] = $request->email;

            $mail_body = view('email-templates.owner-verify-template', $data)->render();
            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $request->email,
                'mail_recipient_name' => $request->name,
                'mail_subject' => 'Verify Owner Account',
                'mail_body' => $mail_body,
            );
            if (sendEmail($mailConfig)) {
                return redirect()->route('owner.register-success');
            } else {
                return redirect()->route('owner.register')->with('fail', 'Something went wrong while sending verification link.');
            }
        } else {
            return redirect()->route('owner.register')->with('fail', 'Something went wrong.');
        }
    }

    public function verifyAccount(Request $request, $token)
    {
        $verifyToken = VerificationToken::where('token', $token)->first();
        if (!is_null($verifyToken)) {
            $user = User::where('email', $verifyToken->email)->first();
            if ($user && $user->role === 'owner') {
                $owner = Owner::where('user_id', $user->id)->first();
                if ($owner && !$owner->verified) {
                    $owner->verified = 1;
                    $owner->email_verified_at = Carbon::now();
                    $owner->save();
                    return redirect()->route('owner.login')->with('success', 'Your E-mail is verified. Login with your account and complete your account profile.');
                } else {
                    return redirect()->route('owner.login')->with('info', 'Your E-Mail is already verified. You already can Login with this account.');
                }
            } else {
                return redirect()->route('owner.register')->with('fail', 'Bukan Pengguna Owner.');
            }
        } else {
            return redirect()->route('owner.register')->with('fail', 'Invalid Token.');
        }
    }
    public function registerSuccess(Request $request)
    {
        return view('back.pages.owner.register-success');
    }

    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:users,email,role,owner',
                'password' => 'required|min:5|max:45'
            ], [
                'login_id.required' => 'Masukkan Username atau Email',
                'login_id.email' => 'Email salah',
                'login_id.exists' => 'Tidak ada Email ini',
                'password.required' => 'Masukkan Password'
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:users,username,role,owner',
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
            'role' => 'owner'
        );

        if (Auth::attempt($creds)) {
            $user = Auth::user();
            $owner = Owner::where('user_id', $user->id)->first();
            if (!$owner->verified) {
                Auth::logout();
                $verifyToken = VerificationToken::where('email', $user->email)->first();
                if (!$verifyToken || $verifyToken->updated_at->lt(Carbon::now()->subMinutes(15))) {
                    $token = base64_encode(Str::random(64));
                    if (!$verifyToken) {
                        VerificationToken::create([
                            'user_type' => 'owner',
                            'email' => $user->email,
                            'token' => $token,
                        ]);
                    } else {
                        $verifyToken->update(['token' => $token, 'updated_at' => $this->now->toDateTimeString()]);
                    }
                    $actionLink = route('owner.verify', ['token' => $token]);
                    $data = [
                        'action_link' => $actionLink,
                        'owner_name' => $user->name,
                        'owner_username' => $user->username,
                        'owner_email' => $user->email,
                    ];
                    $mail_body = view('email-templates.owner-verify-template', $data)->render();
                    $mailConfig = array(
                        'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                        'mail_from_name' => env('EMAIL_FROM_NAME'),
                        'mail_recipient_email' => $user->email,
                        'mail_recipient_name' => $user->name,
                        'mail_subject' => 'Verify Owner Account',
                        'mail_body' => $mail_body,
                    );
                    if (sendEmail($mailConfig)) {
                        return redirect()->route('owner.login')->with('fail', 'Your Account is not verified. We have sent a new verification link to your email. Please check your email and click on the link to verify your account.');
                    } else {
                        return redirect()->route('owner.login')->with('fail', 'Your Account is not verified. Something went wrong while sending verification link. Please contact support for assistance.');
                    }
                } else {
                    return redirect()->route('owner.login')->with('info', 'A verification link was sent to your email address within the last 15 minutes. Please check your email and verify your account.');
                }
                return redirect()->route('owner.login')->with('info', 'We Send Verification Link to Your Email, please check your latest email and verfify your account.');
            } else {
                return redirect()->route('owner.home')->with('success', "Welcome to Owner's Home Page");
            }
        } else {
            return redirect()->route('owner.login')->withInput()->with('fail', 'wrong password');
        }
    }
    //logout
    public function logoutHandler(Request $request)
    {
        Auth::logout();
        return redirect()->route('owner.login')->with('fail', 'akun anda telah Log out dari sistem.');
    }
    public function forgotPassword(Request $request)
    {
        $data = [
            'pageTitle' => 'Forgot Password'
        ];
        return view('back.pages.owner.auth.forgot', $data);
    }
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

        $user = User::where('email', $request->email)->first();
        if ($user && $user->role === 'owner') {
            $owner = Owner::where('user_id', $user->id)->first();
            if ($owner) {
                $token = base64_encode(Str::random(64));
                $oldToken = DB::table('password_reset_tokens')
                    ->where(['email' => $user->email, 'guard' => 'owner'])
                    ->first();

                if ($oldToken) {
                    //update token
                    DB::table('password_reset_tokens')
                        ->where(['email' => $user->email, 'guard' => 'owner'])
                        ->update([
                            'token' => $token,
                            'created_at' => Carbon::now()
                        ]);
                } else {
                    DB::table('password_reset_tokens')->insert([
                        'email' => $request->email,
                        'guard' => 'owner',
                        'token' => $token,
                        'created_at' => Carbon::now()
                    ]);
                }

                $actionLink = route('owner.reset-password', ['token' => $token, 'email' => $request->email]);
                $data['actionLink'] = $actionLink;
                $data['user'] = $user;
                $mail_body = view('email-templates.owner-forgot-email-template', $data)->render();
                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $user->email,
                    'mail_recipient_name' => $user->name,
                    'mail_subject' => 'Reset password',
                    'mail_body' => $mail_body,
                );
                if (sendEmail($mailConfig)) {
                    return redirect()->route('owner.forgot-password')->with('success', 'We have e-mailed your password reset link.');
                } else {
                    return redirect()->route('owner.forgot-password')->with('fail', 'Something went wrong!');
                }
            } else {
                return redirect()->route('owner.forgot-password')->with('fail', 'No owner associated with this email.');
            }
        } else {
            return redirect()->route('owner.forgot-password')->with('fail', 'Invalid owner email.');
        }
    }

    //reset password & email start
    public function showResetForm(Request $request, $token = null)
    {
        $get_token = DB::table('password_reset_tokens')
            ->where(['token' => $token, 'guard' => 'owner'])
            ->first();
        if ($get_token) {
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $get_token->created_at)->diffInMinutes(Carbon::now());
            if ($diffMins > ConstDefaults::tokenExpiredMinutes) {
                return redirect()->route('owner.forgot-password', ['token' => $token])->with('fail', 'Token expired, request another reset password link.');
            } else {
                return view('back.pages.owner.auth.reset')->with(['token' => $token]);
            }
        } else {
            return redirect()->route('owner.forgot-password', ['token' => $token])->with('fail', 'Invalid token!, request another reset password link');;
        }
    }

    public function resetPasswordHandler(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:5|max:45|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation' => 'required'
        ]);

        $token = DB::table('password_reset_tokens')
            ->where(['token' => $request->token, 'guard' => 'owner'])
            ->first();
        if (!$token) {
            return redirect()->route('owner.reset-password')->with('fail', 'Reset Password telah kadaluarsa, silahkan reset ulang kembali.');
        }
        // GET owner details
        $user = User::where('email', $token->email)->first();

        //update owner password
        if ($user && $user->role === 'owner') {
            $owner = Owner::where('user_id', $user->id)->first();
            if (!$owner) {
                return redirect()->route('owner.reset-password')->with('fail', 'Tidak Ada Email Owner ini.');
            }
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            //delete token record
            DB::table('password_reset_tokens')->where([
                'email' => $user->email,
                'token' => $request->token,
                'guard' => 'owner',
            ])->delete();

            //send email to notify owner
            $data['user'] = $user;
            $data['new_password'] = $request->new_password;
            $mail_body = view('email-templates.owner-reset-email-template', $data);

            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $user->email,
                'mail_recipient_name' => $user->name,
                'mail_subject' => 'Password Changed',
                'mail_body' => $mail_body
            );

            sendEmail($mailConfig);
            return redirect()->route('owner.login')->with('success', 'Done!, Your password has been changed, Use new password to login.');
        } else {
            return redirect()->route('owner.reset-password')->with('fail', 'Invalid owner email.');
        }
    }
    //reset password & email end

    //profile page start

    public function profileView(Request $request)
    {
        $owner = null;
        if (Auth::check()) {
            $userId = Auth::id();
            $user = User::where('id', $userId)
                ->where('role', 'owner')
                ->first();
        }
        return view('back.pages.owner.profile', compact('user'));
    }

    public function changeProfilePicture(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->role === 'owner') {
            $path = 'images/users/owners/';
            $file = $request->file('ownerProfilePictureFile');
            $old_picture = $user->picture;
            $file_path = $path . $old_picture;
            $filename = 'OWNER_IMG_' . rand(2, 1000) . $user->id . time() . uniqid() . '.jpg';
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
    public function changeKtpImage(Request $request)
    {
        $user = auth()->user();
        if ($user && $user->role === 'owner') {
            $owner = Owner::where('user_id', $user->id)->firstOrFail();
            $path = 'images/users/owners/KTP_owner';
            $file = $request->file('ownerKtpImageFile');
            $old_ktp = $owner->getAttributes()['ktp'];
            $file_path = $path . $old_ktp;
            $filename = 'OWNER_KTP_IMG_' . rand(2, 1000) . $owner->id . time() . uniqid() . '.jpg';

            $upload = $file->move(public_path($path), $filename);

            if ($upload) {
                if ($old_ktp != null && File::exists(public_path($file_path))) {
                    File::delete(public_path($file_path));
                }
                $owner->update(['ktp' => $filename]);
                return response()->json(['status' => 1, 'msg' => 'Your ID picture has been successfully uploaded']);
            } else {
                return response()->json(['status' => 0, 'msg' => 'Something went wrong.']);
            }
        }
        return response()->json(['status' => 0, 'msg' => 'Unauthorized access.']);
    }
    public function changeLogoImage(Request $request)
    {
        $user = auth()->user();
        if ($user && $user->role === 'owner') {
            $owner = Owner::where('user_id', $user->id)->firstOrFail();
            $path = 'images/users/owners/LOGO_owner';
            $file = $request->file('ownerLogoImageFile');
            $old_logo = $owner->getAttributes()['logo'];
            $file_path = $path . $old_logo;
            $filename = 'OWNER_LOGO_IMG_' . rand(2, 1000) . $owner->id . time() . uniqid() . '.jpg';

            $upload = $file->move(public_path($path), $filename);

            if ($upload) {
                if ($old_logo != null && File::exists(public_path($file_path))) {
                    File::delete(public_path($file_path));
                }
                $owner->update(['logo' => $filename]);
                return response()->json(['status' => 1, 'msg' => 'Your Profile Company has been successfully uploaded']);
            } else {
                return response()->json(['status' => 0, 'msg' => 'Something went wrong.']);
            }
        } else {
            return response()->json(['status' => 0, 'msg' => 'Unauthorized access.']);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
        $owners = User::where('role', 'owner')->get();
        $data = [
            'pageTitle' => "Owner List",
            'owner' => $owners
        ];

        return view('back.pages.admin.manage-users.owner.owner-list', $data);
    }
    public function create()
    {
        $data = [
            'pageTitle' => 'Add Owner',
        ];
        return view('back.pages.admin.manage-users.owner.add-owner', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:5|unique:users,name',
            'username' => 'required|min:5|unique:users,username|regex:/^\S*$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:45',
            'handphone' => 'required|numeric|min:9',
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
            'handphone.numeric' => ':Attribute dalam bentuk nomor.',
            'password_confirmation.required' => ':Attribute is required.',
            'password_confirmation.min' => ':Attribute must be at least 5 characters.',
            'password_confirmation.max' => ':Attribute may not be greater than 45 characters.',
            'handphone.min' => ':Attribute must be at least 9 characters.',
            'address.required' => ':Attribute is required.',
            'address.string' => ':Attribute must be a string.',
            'address.max' => ':Attribute may not be greater than 255 characters.',
        ]);
        $user = new User();
        $user->name = $validatedData['name'];
        $user->role = 'owner';
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->handphone = $validatedData['handphone'];
        $user->address = $validatedData['address'];
        $saved = $user->save();

        if ($saved) {
            $owner = new Owner();
            $owner->user_id = $user->id;
            $owner->verified = 0;
            $owner->save();
            return redirect()->route('admin.user.owner.index')->with('success', '<b>' . ucfirst($validatedData['name']) . '</b> user has been added');
        } else {
            return redirect()->route('admin.user.owner.create')->with('fail', 'something went wrong, try again');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $owner_id = $id;
        $owner = User::findOrFail($owner_id);
        $data = [
            'pageTitle' => 'Edit Owner User',
            'owner' => $owner
        ];
        return view('back.pages.admin.manage-users.owner.edit-owner', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $owner_id = $id;
        $owner = User::findOrFail($owner_id);

        $request->validate([
            'name' => 'required|min:5|unique:users,name,' . $owner_id,
            'username' => 'required|min:5|unique:users,username,' . $owner_id . '|regex:/^\S*$/',
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
            'handphone.min' => ':Attribute must be at least 9 characters.',
            'handphone.max' => ':Attribute may not be greater than 15 characters.',
            'address.required' => ':Attribute is required.',
            'address.string' => ':Attribute must be a string.',
            'address.max' => ':Attribute may not be greater than 255 characters.',
        ]);
        $owner->name = $request->name;
        $owner->username = $request->username;
        $owner->handphone = $request->handphone;
        $owner->address = $request->address;
        $saved = $owner->save();
        if ($saved) {
            return redirect()->route('admin.user.owner.index', ['id' => $owner_id])->with('success', 'User <b>' . ucfirst($request->name) . '</b> has been updated');
        } else {
            return redirect()->route('admin.user.owner.edit', ['id' => $owner_id])->with('fail', 'Something went wrong, try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $owner_id = $id;
        $owner = User::findOrFail($owner_id);
        $owner_name = $owner->name;

        $delete = $owner->delete();
        if ($delete) {
            return redirect()->route('admin.user.owner.index')->with('success', 'User <b>' . ucfirst($owner_name) . '</b> deleted successfully');
        } else {
            return redirect()->route('admin.user.owner.index')->with('fail', "User <b>" . ucfirst($owner_name) . "</b> can't deleted, Try again");
        }
    }
}
