<?php

namespace App\Livewire;

use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class OwnerProfile extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    protected $queryString = ['tab' => ['keep' => true]];
    public $name, $email, $username, $user_id, $handphone, $address, $ktp, $logo;
    public $current_password, $new_password, $new_password_confirmation;

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }
    public function mount()
    {
        $this->tab = request()->tab ? request()->tab : $this->tabname;

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'owner') {
                $this->user_id = $user->id;
                $this->name = $user->name;
                $this->email = $user->email;
                $this->username = $user->username;
                $this->handphone = $user->handphone;
                $this->address = $user->address;
                $this->ktp = $user->owner->ktp; // Pastikan kolom 'ktp' tersedia di tabel users atau relasinya
                $this->logo = $user->owner->logo; // Pastikan kolom 'logo' tersedia di tabel users atau relasinya
            }
        }
    }
    public function updateOwnerPersonalDetails()
    {
        $this->validate([
            'name' => 'required|min:5',
            'username' => 'required|min:3|unique:users,username,' . $this->user_id,
        ]);
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'owner') {
                $user->id = $this->user_id;
                $user->name = $this->name;
                $user->username = $this->username;
                $user->handphone = $this->handphone;
                $user->address = $this->address;
                $update = $user->save();
            } else {
                return redirect()->route('owner.profile')->with('fail', 'Bukan pengguna Owner.');
            }
        }
        if ($update) {
            return redirect()->route('owner.profile')->with('success', 'Your personal details have been updated');
            $this->dispatch('updateAdminOwnerHeaderInfo');
        } else {
            return redirect()->route('owner.profile')->with('fail', 'Something went wrong.');
        }
    }
    public function showToastr($type, $message)
    {
        return $this->dispatch('showToastr', [
            'type' => $type,
            'message' => $message,
        ]);
    }
    public function updatePassword()
    {
        $this->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    if (!Hash::check($value, $user->password)) {
                        return $fail(__('The current password is incorrect'));
                    }
                }
            ],
            'new_password' => 'required|min:5|max:45|confirmed'
        ]);
        $user = auth()->user();
        $query = $user->update([
            'password' => Hash::make($this->new_password)
        ]);
        if ($query) {
            //send notification
            $data = array(
                'user' => $user,
                'new_password' => $this->new_password
            );

            $mail_body = view('email-templates.owner-reset-email-template', $data)->render();

            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' =>  $user->email,
                'mail_recipient_name' => $user->name,
                'mail_subject' => 'Password Changed',
                'mail_body' => $mail_body
            );

            sendEmail($mailConfig);

            $this->current_password = $this->new_password = $this->new_password_confirmation = null;
            $this->showToastr('success', 'password successfully changed, check your email for details');
        } else {
            $this->showToastr('error', 'Something went wrong');
        }
    }
    public function render()
    {
        $user = Auth::user();
        if ($user && $user->role === 'owner') {
            return view('livewire.owner-profile', ['user' => $user]);
        }
        return view('livewire.owner-profile', ['user' => null]);
    }
}
