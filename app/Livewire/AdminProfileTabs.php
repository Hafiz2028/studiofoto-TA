<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminProfileTabs extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    protected $queryString = ['tab' => ['keep' => true]];
    public $name, $email, $username, $admin_id;
    public $current_password, $new_password, $new_password_confirmation;

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }
    public function mount()
    {
        $this->tab = request()->tab ? request()->tab : $this->tabname;

        $user = User::findOrFail(auth()->id());
        if (Auth::check() && $user->role === 'admin') {
            $this->admin_id = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->username = $user->username;
        } else {
            return redirect()->back()->with('fail', 'Bukan User Admin.');
        }
    }
    public function updateAdminPersonalDetails()
    {
        $this->validate([
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email,' . $this->admin_id,
            'username' => 'required|min:3|unique:users,username,' . $this->admin_id
        ]);
        User::find($this->admin_id)
            ->update([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username
            ]);
        $this->dispatch('updateAdminOwnerHeaderInfo');
        $this->dispatch('updateAdminInfo', [
            'adminName' => $this->name,
            'adminEmail' => $this->email
        ]);
        return redirect()->route('admin.profile')->with('success', 'Your personal details have been updated');
        $this->showToastr('success', 'Your Personal details have been updated.');
    }
    public function updatePassword()
    {
        $this->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    if ($user->role !== 'admin') {
                        return $fail(__('Unauthorized access.'));
                    }
                    if (!Hash::check($value, $user->password)) {
                        return $fail(__('The current password is incorrect'));
                    }
                }
            ],
            'new_password' => 'required|min:5|max:45|confirmed'
        ]);
        $admin = auth()->user();
        $admin->password = Hash::make($this->new_password);
        $query = $admin->save();
        if ($query) {
            //send notification
            $data = [
                'admin' => $admin,
                'new_password' => $this->new_password
            ];

            $mail_body = view('email-templates.admin-reset-email-template', $data)->render();

            $mailConfig = [
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $admin->email,
                'mail_recipient_name' => $admin->name,
                'mail_subject' => 'Password Changed',
                'mail_body' => $mail_body
            ];

            sendEmail($mailConfig);

            $this->current_password = $this->new_password = $this->new_password_confirmation = null;
            $this->showToastr('success', 'password successfully changed');
        } else {
            $this->showToastr('error', 'Something went wrong');
        }
    }

    public function showToastr($type, $message)
    {
        return $this->dispatch('showToastr', [
            'type' => $type,
            'message' => $message
        ]);
    }

    public function render()
    {
        return view('livewire.admin-profile-tabs');
    }
}
