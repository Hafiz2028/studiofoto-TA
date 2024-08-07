<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\ConstGuards;
use App\Helpers\ConstDefaults;
use App\Models\Owner;
use App\Models\Venue;
use App\Models\Rent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\VerificationToken;

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
        $owner = Auth::guard('owner')->user();
        $venues = Venue::where('owner_id', $owner->id)->get(['id', 'status']);
        $venueIds = $owner->venues->pluck('id');
        $rents = Rent::whereHas('servicePackageDetail.servicePackage.serviceEvent', function ($query) use ($venueIds) {
            $query->whereIn('venue_id', $venueIds);
        })->get(['id', 'rent_status']);

        $data = [
            'pageTitle' => 'Owner Home Page',
            'venues' => $venues,
            'rents' => $rents,
            'owner' => $owner,
        ];
        return view('back.pages.owner.home', $data);
    }
    public function getRentEvents($ownerId)
    {
        // Mengambil data rents dan rent_details
        $rentEvents = Rent::with([
            'servicePackageDetail.servicePackage.serviceEvent.venue',
            'rentDetails.openingHour.hour'
        ])
            ->whereHas('servicePackageDetail.servicePackage.serviceEvent.venue', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->get();

        // Mengirimkan data dalam format JSON
        return response()->json($rentEvents);
    }
    public function createOwner(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:owners',
            'email' => 'required|email|unique:owners',
            'password' => 'min:5|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:5',
        ]);
        $owner = new Owner();
        $owner->name = $request->name;
        $owner->username = $request->username;
        $owner->email = $request->email;
        $owner->password = Hash::make($request->password);
        $saved = $owner->save();
        if ($saved) {
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
            $owner = Owner::where('email', $verifyToken->email)->first();
            if (!$owner->verified) {
                $owner->verified = 1;
                $owner->email_verified_at = Carbon::now();
                $owner->save();
                return redirect()->route('owner.login')->with('success', 'Your E-mail is verified. Login with your account and complete your account profile.');
            } else {
                return redirect()->route('owner.login')->with('info', 'Your E-Mail is already verified. You already can Login with this account.');
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
                'login_id' => 'required|email|exists:owners,email',
                'password' => 'required|min:5|max:45'
            ], [
                'login_id.required' => 'Email or Username is required',
                'login_id.email' => 'Invalid email address',
                'login_id.exists' => 'Email is not exists in system',
                'password.required' => 'Password is required'
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:owners,username',
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

        if (Auth::guard('owner')->attempt($creds)) {
            $user = auth('owner')->user();
            if (!$user->verified) {
                auth('owner')->logout();
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
                    $data['action_link'] = $actionLink;
                    $data['owner_name'] = $user->name;
                    $data['owner_username'] = $user->username;
                    $data['owner_email'] = $user->email;
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
        Auth::guard('owner')->logout();
        return redirect()->route('owner.login')->with('fail', 'You are logged out');
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
            'email' => 'required|email|exists:owners,email'
        ], [
            'email.required' => 'The :attribute is required',
            'email.email' => 'Invalid email address',
            'email.exists' => 'The :attribute is not exists in system'
        ]);

        //Get owner details
        $owner = Owner::where('email', $request->email)->first();

        //generate token
        $token = base64_encode(Str::random(64));

        //check if there is an existing reset password token
        $oldToken = DB::table('password_reset_tokens')
            ->where(['email' => $owner->email, 'guard' => ConstGuards::OWNER])
            ->first();

        if ($oldToken) {
            //update token
            DB::table('password_reset_tokens')
                ->where(['email' => $owner->email, 'guard' => ConstGuards::OWNER])
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        } else {
            DB::table('password_reset_tokens')->insert([
                'email' => $owner->email,
                'guard' => ConstGuards::OWNER,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }

        $actionLink = route('owner.reset-password', ['token' => $token, 'email' => urlencode($owner->email)]);

        $data['actionLink'] = $actionLink;
        $data['owner'] = $owner;
        $mail_body = view('email-templates.owner-forgot-email-template', $data)->render();

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $owner->email,
            'mail_recipient_name' => $owner->name,
            'mail_subject' => 'Reset password',
            'mail_body' => $mail_body,
        );

        if (sendEmail($mailConfig)) {
            return redirect()->route('owner.forgot-password')->with('success', 'We have e-mailed your password reset link.');
        } else {
            return redirect()->route('owner.forgot-password')->with('fail', 'Something went wrong!');
        }
    }

    //reset password & email start
    public function showResetForm(Request $request, $token = null)
    {
        $get_token = DB::table('password_reset_tokens')
            ->where(['token' => $token, 'guard' => ConstGuards::OWNER])
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
            ->where(['token' => $request->token, 'guard' => ConstGuards::OWNER])
            ->first();

        // GET owner details
        $owner = Owner::where('email', $token->email)->first();

        //update owner password
        Owner::where('email', $owner->email)->update([
            'password' => Hash::make($request->new_password)
        ]);

        //delete token record
        DB::table('password_reset_tokens')->where([
            'email' => $owner->email,
            'token' => $request->token,
            'guard' => ConstGuards::OWNER
        ])->delete();

        //send email to notify owner
        $data['owner'] = $owner;
        $data['new_password'] = $request->new_password;
        $mail_body = view('email-templates.owner-reset-email-template', $data);

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $owner->email,
            'mail_recipient_name' => $owner->name,
            'mail_subject' => 'Password Changed',
            'mail_body' => $mail_body
        );

        sendEmail($mailConfig);
        return redirect()->route('owner.login')->with('success', 'Done!, Your password has been changed, Use new password to login.');
    }
    //reset password & email end

    //profile page start

    public function profileView(Request $request)
    {
        $owner = null;
        if (Auth::guard('owner')->check()) {
            $owner = Owner::findOrFail(auth()->id());
        }
        return view('back.pages.owner.profile', compact('owner'));
    }

    public function changeProfilePicture(Request $request)
    {
        $owner = Owner::findOrFail(auth('owner')->id());
        $path = 'images/users/owners/';
        $file = $request->file('ownerProfilePictureFile');
        $old_picture = $owner->getAttributes()['picture'];
        $file_path = $path . $old_picture;
        $filename = 'OWNER_IMG_' . rand(2, 1000) . $owner->id . time() . uniqid() . '.jpg';

        $upload = $file->move(public_path($path), $filename);

        if ($upload) {
            if ($old_picture != null && File::exists(public_path($file_path))) {
                File::delete(public_path($file_path));
            }
            $owner->update(['picture' => $filename]);
            return response()->json(['status' => 1, 'msg' => 'Your profile picture has been successfully uploaded']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong.']);
        }
    }
    public function changeKtpImage(Request $request)
    {
        $owner = Owner::findOrFail(auth('owner')->id());
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
    public function changeLogoImage(Request $request)
    {
        $owner = Owner::findOrFail(auth('owner')->id());
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
    }
    /**
     * Show the form for creating a new resource.
     */
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
            'name' => 'required|min:5|unique:owners,name',
            'username' => 'required|min:5|unique:owners,username|regex:/^\S*$/',
            'email' => 'required|email|unique:owners,email',
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
        $owner = new Owner();

        $owner->name = $validatedData['name'];
        $owner->username = $validatedData['username'];
        $owner->email = $validatedData['email'];
        $owner->password = Hash::make($validatedData['password']);
        $owner->handphone = $validatedData['handphone'];
        $owner->address = $validatedData['address'];
        $saved = $owner->save();

        if ($saved) {
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
        $owner = Owner::findOrFail($owner_id);
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
        $owner = Owner::findOrFail($owner_id);

        $request->validate([
            'name' => 'required|min:5|unique:owners,name,' . $owner_id,
            'username' => 'required|min:5|unique:owners,username,' . $owner_id . '|regex:/^\S*$/',
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
        $owner = Owner::findOrFail($owner_id);
        $owner_name = $owner->name;

        $delete = $owner->delete();
        if ($delete) {
            return redirect()->route('admin.user.owner.index')->with('success', 'User <b>' . ucfirst($owner_name) . '</b> deleted successfully');
        } else {
            return redirect()->route('admin.user.owner.index')->with('fail', "User <b>" . ucfirst($owner_name) . "</b> can't deleted, Try again");
        }
    }

}
