<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminOwnerHeaderProfileInfo extends Component
{
    public $admin;
    public $owner;

    public $listeners = [
        'updateAdminOwnerHeaderInfo' => '$refresh'
    ];


    public function mount(){
        if (Auth::guard('admin')->check()){
            $this->admin = Admin::findOrFail(auth()->id());
        }
    }
    public function render()
    {
        return view('livewire.admin-owner-header-profile-info');
    }
}
