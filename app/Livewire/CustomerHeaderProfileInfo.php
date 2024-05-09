<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerHeaderProfileInfo extends Component
{
    public $customer;

    public $listeners = [
        'updateCustomerHeaderInfo' => '$refresh'
    ];
    public function mount()
    {
        if (Auth::guard('customer')->check()) {
            $customer = Customer::find(auth()->id());

            if ($customer) {
                $this->customer = $customer;
            }
        }
    }
    public function render()
    {
        return view('livewire.customer-header-profile-info');
    }
}
