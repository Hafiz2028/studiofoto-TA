<?php
 
namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminOwnerHeaderProfileInfo extends Component
{
    public $user;

    public $listeners = [
        'updateAdminOwnerHeaderInfo' => '$refresh'
    ];


    public function mount()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $this->user = User::findOrFail($userId);
        }
    }
    public function render()
    {

        return view('livewire.admin-owner-header-profile-info');
    }
}
