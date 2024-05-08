<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;

class OwnerProfile extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    protected $queryString = ['tab' => ['keep' => true]];
    public $name, $email, $username, $owner_id, $handphone, $address, $ktp;
    public $current_password, $new_password, $new_password_confirmation;

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }
    public function mount()
    {
        $this->tab = request()->tab ? request()->tab : $this->tabname;

        if (Auth::guard('owner')->check()) {
            $owner = Owner::findOrFail(auth()->id());
            $this->owner_id = $owner->id;
            $this->name = $owner->name;
            $this->email = $owner->email;
            $this->username = $owner->username;
            $this->handphone = $owner->handphone;
            $this->address = $owner->address;
            $this->ktp = $owner->ktp;
        }
    }
    public function updateOwnerPersonalDetails()
    {
        $this->validate([
            'name' => 'required|min:5',
            'username' => 'required|min:3|unique:owners,username,' . $this->owner_id,
        ]);
        $owner = Owner::findOrFail(auth('owner')->id());
        $owner->name = $this->name;
        $owner->username = $this->username;
        $owner->handphone = $this->handphone;
        $owner->address = $this->address;
        $update = $owner->save();

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
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Owner::find(auth('owner')->id())->password)) {
                        return $fail(__('The current password is incorrect'));
                    }
                }
            ],
            'new_password' => 'required|min:5|max:45|confirmed'
        ]);

        $query = Owner::findOrFail(auth('owner')->id())->update([
            'password' => Hash::make($this->new_password)
        ]);
        if ($query) {
            //send notification
            $_owner = Owner::findOrFail($this->owner_id);
            $data = array(
                'owner' => $_owner,
                'new_password' => $this->new_password
            );

            $mail_body = view('email-templates.owner-reset-email-template', $data)->render();

            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $_owner->email,
                'mail_recipient_name' => $_owner->name,
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
        return view('livewire.owner-profile', [
            'owner' => Owner::findOrFail(auth('owner')->id())
        ]);
    }
}
