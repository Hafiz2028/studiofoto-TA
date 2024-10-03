<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\ConstDefaults;
use App\Models\Customer;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use App\Models\VerificationToken;

class CustomerController extends Controller
{
    public $now;

    public function __construct()
    {
        $this->now = Carbon::now()->tz('Asia/Jakarta');
    }
    public function login(Request $request)
    {
        $data = [
            'pageTitle' => 'Login Customer',
        ];
        return view('back.pages.customer.auth.login', $data);
    }
    public function register(Request $request)
    {
        $data = [
            'pageTitle' => 'Register Customer',
        ];
        return view('back.pages.customer.auth.register', $data);
    }
    public function createCustomer(Request $request)
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
        $user->password = Hash::make($request->password);
        $user->role = 'customer';
        $saved = $user->save();
        if ($saved) {
            $customer = new Customer();
            $customer->user_id = $user->id;
            $customer->verified = 1;
            $customer->save();
            $token = base64_encode(Str::random(64));
            VerificationToken::create([
                'user_type' => 'customer',
                'email' => $request->email,
                'token' => $token
            ]);

            $actionLink = route('customer.verify', ['token' => $token]);
            $data['action_link'] = $actionLink;
            $data['customer_name'] = $request->name;
            $data['customer_username'] = $request->username;
            $data['customer_email'] = $request->email;

            $mail_body = view('email-templates.customer-verify-template', $data)->render();
            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $request->email,
                'mail_recipient_name' => $request->name,
                'mail_subject' => 'Verify Customer Account',
                'mail_body' => $mail_body,
            );
            if (sendEmail($mailConfig)) {
                return redirect()->route('customer.login')->with('success','Akun berhasil dibuat, silahkan melakukan login');
            } else {
                return redirect()->route('customer.register')->with('fail', 'Something went wrong while sending verification link.');
            }
        } else {
            return redirect()->route('customer.register')->with('fail', 'Something went wrong.');
        }
    }

    public function verifyAccount(Request $request, $token)
    {
        $verifyToken = VerificationToken::where('token', $token)->first();
        if (!is_null($verifyToken)) {
            $user = User::where('email', $verifyToken->email)->first();
            if ($user && $user->role === 'customer') {
                $customer = Customer::where('user_id', $user->id)->first();
                if ($customer && !$customer->verified) {
                    $customer->verified = 1;
                    $customer->email_verified_at = Carbon::now();
                    $customer->save();
                    return redirect()->route('customer.login')->with('success', 'Your E-mail is verified. Login with your account and complete your account profile.');
                } else {
                    return redirect()->route('customer.login')->with('info', 'Your E-Mail is already verified. You already can Login with this account.');
                }
            } else {
                return redirect()->route('customer.register')->with('fail', 'Bukan Pengguna Customer.');
            }
        } else {
            return redirect()->route('customer.register')->with('fail', 'Invalid Token.');
        }
    }
    public function registerSuccess(Request $request)
    {
        return view('back.pages.customer.register-success');
    }

    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:users,email,role,customer',
                'password' => 'required|min:5|max:45'
            ], [
                'login_id.required' => 'Masukkan Username atau Email',
                'login_id.email' => 'Email salah',
                'login_id.exists' => 'Tidak ada Email ini',
                'password.required' => 'Masukkan Password'
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:users,username,role,customer',
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
            'role' => 'customer'
        );

        if (Auth::attempt($creds)) {
            $user = Auth::user();
            $customer = Customer::where('user_id', $user->id)->first();
            if (!$customer->verified) {
                Auth::logout();
                $verifyToken = VerificationToken::where('email', $user->email)->first();
                if (!$verifyToken || $verifyToken->updated_at->lt(Carbon::now()->subMinutes(15))) {
                    $token = base64_encode(Str::random(64));
                    if (!$verifyToken) {
                        VerificationToken::create([
                            'user_type' => 'customer',
                            'email' => $user->email,
                            'token' => $token,
                        ]);
                    } else {
                        $verifyToken->update(['token' => $token, 'updated_at' => $this->now->toDateTimeString()]);
                    }
                    $actionLink = route('customer.verify', ['token' => $token]);
                    $data['action_link'] = $actionLink;
                    $data['customer_name'] = $user->name;
                    $data['customer_username'] = $user->username;
                    $data['customer_email'] = $user->email;
                    $mail_body = view('email-templates.customer-verify-template', $data)->render();
                    $mailConfig = array(
                        'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                        'mail_from_name' => env('EMAIL_FROM_NAME'),
                        'mail_recipient_email' => $user->email,
                        'mail_recipient_name' => $user->name,
                        'mail_subject' => 'Verify Customer Account',
                        'mail_body' => $mail_body,
                    );
                    if (sendEmail($mailConfig)) {
                        return redirect()->route('customer.login')->with('fail', 'Your Account is not verified. We have sent a new verification link to your email. Please check your email and click on the link to verify your account.');
                    } else {
                        return redirect()->route('customer.login')->with('fail', 'Your Account is not verified. Something went wrong while sending verification link. Please contact support for assistance.');
                    }
                } else {
                    return redirect()->route('customer.login')->with('info', 'A verification link was sent to your email address within the last 15 minutes. Please check your email and verify your account.');
                }
                return redirect()->route('customer.login')->with('info', 'We Send Verification Link to Your Email, please check your latest email and verfify your account.');
            } else {
                return redirect()->route('home')->with('success', "Welcome to FotoYuk Marketplace! Hope you found your Studio :D");
            }
        } else {
            return redirect()->route('customer.login')->withInput()->with('fail', 'wrong password');
        }
    }
    //logout
    public function logoutHandler(Request $request)
    {
        Auth::logout();
        return redirect()->route('customer.home')->with('fail', 'akun anda telah Log out dari sistem.');
    }
    public function forgotPassword(Request $request)
    {
        $data = [
            'pageTitle' => 'Forgot Password'
        ];
        return view('back.pages.customer.auth.forgot', $data);
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

        //Get customer details
        $user = User::where('email', $request->email)->first();
        if ($user && $user->role === 'customer') {
            $customer = Customer::where('user_id', $user->id)->first();
            if ($customer) {
                //generate token
                $token = base64_encode(Str::random(64));

                //check if there is an existing reset password token
                $oldToken = DB::table('password_reset_tokens')
                    ->where(['email' => $customer->email, 'guard' => 'customer'])
                    ->first();

                if ($oldToken) {
                    //update token
                    DB::table('password_reset_tokens')
                        ->where(['email' => $customer->email, 'guard' => 'customer'])
                        ->update([
                            'token' => $token,
                            'created_at' => Carbon::now()
                        ]);
                } else {
                    DB::table('password_reset_tokens')->insert([
                        'email' => $customer->email,
                        'guard' => 'customer',
                        'token' => $token,
                        'created_at' => Carbon::now()
                    ]);
                }

                $actionLink = route('customer.reset-password', ['token' => $token, 'email' => $customer->email]);

                $data['actionLink'] = $actionLink;
                $data['user'] = $user;
                $mail_body = view('email-templates.customer-forgot-email-template', $data)->render();

                $mailConfig = array(
                    'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                    'mail_from_name' => env('EMAIL_FROM_NAME'),
                    'mail_recipient_email' => $user->email,
                    'mail_recipient_name' => $user->name,
                    'mail_subject' => 'Reset password',
                    'mail_body' => $mail_body,
                );

                if (sendEmail($mailConfig)) {
                    return redirect()->route('customer.forgot-password')->with('success', 'We have e-mailed your password reset link.');
                } else {
                    return redirect()->route('customer.forgot-password')->with('fail', 'Something went wrong!');
                }
            } else {
                return redirect()->route('customer.forgot-password')->with('fail', 'No Customer associated with this email.');
            }
        } else {
            return redirect()->route('customer.forgot-password')->with('fail', 'Invalid Customer email.');
        }
    }

    //reset password & email start
    public function showResetForm(Request $request, $token = null)
    {
        $get_token = DB::table('password_reset_tokens')
            ->where(['token' => $token, 'guard' => 'customer'])
            ->first();
        if ($get_token) {
            $diffMins = Carbon::createFromFormat('Y-m-d H:i:s', $get_token->created_at)->diffInMinutes(Carbon::now());
            if ($diffMins > ConstDefaults::tokenExpiredMinutes) {
                return redirect()->route('customer.forgot-password', ['token' => $token])->with('fail', 'Token expired, request another reset password link.');
            } else {
                return view('back.pages.customer.auth.reset')->with(['token' => $token]);
            }
        } else {
            return redirect()->route('customer.forgot-password', ['token' => $token])->with('fail', 'Invalid token!, request another reset password link');;
        }
    }

    public function resetPasswordHandler(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:5|max:45|required_with:new_password_confirmation|same:new_password_confirmation',
            'new_password_confirmation' => 'required'
        ]);

        $token = DB::table('password_reset_tokens')
            ->where(['token' => $request->token, 'guard' => 'customer'])
            ->first();
        if (!$token) {
            return redirect()->route('customer.reset-password')->with('fail', 'Reset Password telah kadaluarsa, silahkan reset ulang kembali.');
        }
        // GET customer details
        $user = User::where('email', $token->email)->first();

        //update customer password
        if ($user && $user->role === 'customer') {
            $customer = Customer::where('user_id', $user->id)->first();
            if (!$customer) {
                return redirect()->route('customer.reset-password')->with('fail', 'Tidak Ada Email Customer ini.');
            }
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            //delete token record
            DB::table('password_reset_tokens')->where([
                'email' => $customer->email,
                'token' => $request->token,
                'guard' => 'customer'
            ])->delete();

            //send email to notify customer
            $data['user'] = $user;
            $data['new_password'] = $request->new_password;
            $mail_body = view('email-templates.customer-reset-email-template', $data);

            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $user->email,
                'mail_recipient_name' => $user->name,
                'mail_subject' => 'Password Changed',
                'mail_body' => $mail_body
            );

            sendEmail($mailConfig);
            return redirect()->route('customer.login')->with('success', 'Done!, Your password has been changed, Use new password to login.');
        } else {
            return redirect()->route('customer.reset-password')->with('fail', 'Invalid Customer email.');
        }
    }
    //reset password & email end

    //profile page start
    public function profileView(Request $request)
    {
        $customer = null;
        if (Auth::check()) {
            $userId = Auth::id();
            $user = User::where('id', $userId)
                ->where('role', 'customer')
                ->first();
        }
        return view('front.pages.profile', compact('user'));
    }

    public function changeProfilePicture(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->role === 'customer') {
            $path = 'images/users/customers/';
            $file = $request->file('customerProfilePictureFile');
            $old_picture = $user->picture;
            $file_path = $path . $old_picture;
            $filename = 'CUSTOMER_IMG_' . rand(2, 1000) . $user->id . time() . uniqid() . '.jpg';

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

    //crud cust acc
    public function index()
    {
        $customer = User::where('role', 'customer')->get();
        $data = [
            'pageTitle' => "List Customer",
            'customer' => $customer,
        ];
        return view('back.pages.admin.manage-users.customer.cust-list', $data);
    }
    public function create()
    {
        $data = [
            'pageTitle' => 'Add Customer',
        ];
        return view('back.pages.admin.manage-users.customer.add-cust', $data);
    }
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
        $user = new User();

        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->handphone = $validatedData['handphone'];
        $user->address = $validatedData['address'];
        $user->role = 'customer';
        $saved = $user->save();

        if ($saved) {
            $customer = new Customer();
            $customer->user_id = $user->id;
            $customer->verified = 0;
            $customer->save();
            return redirect()->route('admin.user.customer.index')->with('success', '<b>' . ucfirst($validatedData['name']) . '</b> user has been added');
        } else {
            return redirect()->route('admin.user.customer.create')->with('fail', 'something went wrong, try again');
        }
    }
    public function show(string $id)
    {
        //
    }
    public function edit(string $id)
    {
        $customer_id = $id;
        $customer = User::findOrFail($customer_id);
        $data = [
            'pageTitle' => 'Edit Customer User',
            'customer' => $customer
        ];
        return view('back.pages.admin.manage-users.customer.edit-cust', $data);
    }
    public function update(Request $request, string $id)
    {
        $customer_id = $id;
        $customer = User::findOrFail($customer_id);

        $request->validate([
            'name' => 'required|min:5|unique:users,name,' . $customer_id,
            'username' => 'required|min:5|unique:users,username,' . $customer_id . '|regex:/^\S*$/',
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
        $customer->name = $request->name;
        $customer->username = $request->username;
        $customer->handphone = $request->handphone;
        $customer->address = $request->address;
        $saved = $customer->save();
        if ($saved) {
            return redirect()->route('admin.user.customer.index', ['id' => $customer_id])->with('success', 'User <b>' . ucfirst($request->name) . '</b> has been updated');
        } else {
            return redirect()->route('admin.user.customer.edit', ['id' => $customer_id])->with('fail', 'Something went wrong, try again');
        }
    }
    public function destroy(string $id)
    {
        $customer_id = $id;
        $customer = User::findOrFail($customer_id);
        $customer_name = $customer->name;

        $delete = $customer->delete();
        if ($delete) {
            return redirect()->route('admin.user.customer.index')->with('success', 'User <b>' . ucfirst($customer_name) . '</b> deleted successfully');
        } else {
            return redirect()->route('admin.user.customer.index')->with('fail', "User <b>" . ucfirst($customer_name) . "</b> can't deleted, Try again");
        }
    }
}
