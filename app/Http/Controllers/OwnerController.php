<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use constGuards;
use constDefaults;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class OwnerController extends Controller
{
    //auth
    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:owners,email',
                'password' => 'required|min:5|max:45'
            ], [
                'login_id,required' => 'Email or Username is required',
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
            return redirect()->route('owner.home');
        } else {
            session()->flash('fail', 'wrong password');
            return redirect()->route('owner.login');
        }
    }
    //logout
    public function logoutHandler(Request $request)
    {
        Auth::guard('owner')->logout();
        session()->flash('fail', 'You are logged out');
        return redirect()->route('owner.login');
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
            ->where(['email' => $request->email, 'guard' => constGuards::OWNER])
            ->first();

        if ($oldToken) {
            //update token
            DB::table('password_reset_tokens')
                ->where(['email' => $request->email, 'guard' => constGuards::OWNER])
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        } else {
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'guard' => constGuards::OWNER,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }

        $actionLink = route('owner.reset-password', ['token' => $token, 'email' => $request->email]);

        $data = array(
            'actionLink' => $actionLink,
            'owner' => $owner
        );

        $mail_body = view('email-templates.admin-forgot-email-template', $data)->render();

        $mailConfig = array(
            'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
            'mail_from_name' => env('EMAIL_FROM_NAME'),
            'mail_recipient_email' => $owner->email,
            'mail_recipient_name' => $owner->name,
            'mail_subject' => 'Reset password',
            'mail_body' => $mail_body,
        );

        if (sendEmail($mailConfig)) {
            session()->flash('success', 'We have e-mailed your password reset link.');
            return redirect()->route('owner.forgot-password');
        } else {
            session()->flash('fail', 'Something went wrong!');
            return redirect()->route('owner.forgot-password');
        }
    }

    //reset password & email start
    public function resetPassword(Request $request, $token = null)
    {
        $check_token = DB::table('password_reset_tokens')
            ->where(['token' => $token, 'guard' => constGuards::OWNER])
            ->first();
        if ($check_token) {
            //check if token is not expired
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $check_token->created_at)->diffInMinutes(Carbon::now());

            if ($diffMins > constDefaults::tokenExpiredMinutes) {
                //if token is expired
                session()->flash('fail', 'Token expired, request another reset password link.');
                return redirect()->route('owner.forgot-password', ['token' => $token]);
            } else {
                return view('back.pages.owner.auth.reset-password')->with(['token' => $token]);
            }
        } else {
            session()->flash('fail', 'Invalid token!, request another reset password link');
            return redirect()->route('owner.forgot-password', ['token' => $token]);
        }
    }

    public function resetPasswordHandler(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:5|max:45|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation' => 'required'
        ]);

        $token = DB::table('password_reset_tokens')
            ->where(['token' => $request->token, 'guard' => constGuards::OWNER])
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
            'guard' => constGuards::OWNER
        ])->delete();

        //send email to notify owner
        $data = array(
            'owner' => $owner,
            'new_password' => $request->new_password
        );

        $mail_body = view('email-templates.admin-reset-email-template', $data)->render();

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
            if ($old_picture != null && File::exists(public_path($path . $old_picture))) {
                File::delete(public_path($path . $old_picture));
            }
            $owner->update(['picture' => $filename]);
            return response()->json(['status' => 1, 'msg' => 'Your profile picture has been successfully uploaded']);
        } else {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong.']);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $owner = Owner::all();
        return view('back.pages.admin.manage-users.owner.owner-list', compact('owner'));
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
            'handphone.required' => ':Attribute field is required.',
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
