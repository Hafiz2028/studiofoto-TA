<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerProfile extends Component
{
    public $tab = null;
    public $tabname = 'personal_details';
    protected $queryString = ['tab' => ['keep' => true]];
    public $name, $email, $username, $customer_id, $handphone, $address, $ktp;
    public $current_password, $new_password, $new_password_confirmation;

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }
    public function mount()
    {
        $this->tab = request()->tab ? request()->tab : $this->tabname;

        if (Auth::guard('customer')->check()) {
            $customer = Customer::findOrFail(auth()->id());
            $this->customer_id = $customer->id;
            $this->name = $customer->name;
            $this->email = $customer->email;
            $this->username = $customer->username;
            $this->handphone = $customer->handphone;
            $this->address = $customer->address;
            $this->ktp = $customer->ktp;
        }
    }
    public function updateCustomerPersonalDetails()
    {
        $this->validate([
            'name' => 'required|min:5',
            'username' => 'required|min:3|unique:customers,username,' . $this->customer_id,
        ]);
        $customer = Customer::findOrFail(auth('customer')->id());
        $customer->name = $this->name;
        $customer->username = $this->username;
        $customer->handphone = $this->handphone;
        $customer->address = $this->address;
        $update = $customer->save();

        if ($update) {
            return redirect()->route('customer.profile')->with('success', 'Your personal details have been updated');
            $this->dispatch('updateCustomerHeaderInfo');
        } else {
            return redirect()->route('customer.profile')->with('fail', 'Something went wrong.');
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
                    if (!Hash::check($value, Customer::find(auth('customer')->id())->password)) {
                        return $fail(__('The current password is incorrect'));
                    }
                }
            ],
            'new_password' => 'required|min:5|max:45|confirmed'
        ]);

        $query = Customer::findOrFail(auth('customer')->id())->update([
            'password' => Hash::make($this->new_password)
        ]);
        if ($query) {
            //send notification
            $_customer = Customer::findOrFail($this->customer_id);
            $data = array(
                'customer' => $_customer,
                'new_password' => $this->new_password
            );

            $mail_body = view('email-templates.customer-reset-email-template', $data)->render();

            $mailConfig = array(
                'mail_from_email' => env('EMAIL_FROM_ADDRESS'),
                'mail_from_name' => env('EMAIL_FROM_NAME'),
                'mail_recipient_email' => $_customer->email,
                'mail_recipient_name' => $_customer->name,
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
        return view('livewire.customer-profile', [
            'customer' => Customer::findOrFail(auth('customer')->id())
        ]);
    }
}
