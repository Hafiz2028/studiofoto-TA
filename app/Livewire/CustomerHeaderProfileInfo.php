<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CustomerHeaderProfileInfo extends Component
{
    public $user;

    public $listeners = [
        'updateCustomerHeaderInfo' => '$refresh'
    ];
    public function mount()
    {
        if (Auth::check()) {
            $userId = Auth::id();

            $this->user = User::findOrFail($userId);

            // if ($customer) {
            //     $this->customer = $customer;
            // }
        }
    }
    public function render()
    {
        return view('livewire.customer-header-profile-info');
    }
}
