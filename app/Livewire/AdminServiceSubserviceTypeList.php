<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ServiceType;

class AdminServiceSubserviceTypeList extends Component
{
    // protected $listeners =[
    //     'updateServicesOrdering'
    // ];

    // public function updateServicesOrdering($positions){
    //     foreach($positions as $position){
    //         $index = $position[0];
    //         $newPosition = $position[1];
    //         ServiceType::where('id',$index)->update([
    //             'ordering'=>$newPosition
    //         ]);
    //         $this->showToastr('success','service ordering successfully updated');
    //     }
    // }
    // public function showToastr($type, $message){
    //     return $this->dispatch('showToastr',$type,$message);
    // }
    public function render()
    {
        return view('livewire.admin-service-subservice-type-list',[
            'service_types' =>ServiceType::all()
        ]);

    }
}
