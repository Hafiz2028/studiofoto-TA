<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceType;

class ServiceVenueController extends Controller
{
    public function venueServiceList(Request $request){
        $data = [
            'pageTitle' => "Venue's Services"
        ];
        return view('back.pages.admin.manage-service.service-list', $data);
    }
    public function addService(Request $request){
        $data=[
            'pageTitle' => "Add Service"
        ];
        return view('back.pages.admin.manage-service.add-service',$data);
    }

    public function storeService(Request $request){
        //validate
        $request->validate([
            'service_name' => 'required|min:5|unique:service_types,service_name',
        ],[
            'service_name.required' => ':Attribute is required',
            'service_name.min'=> ':Attribute must contains atleast 5 characters',
            'service_name.unique' => 'This :attribute is already exists',
        ]);
        $service = new ServiceType();
        $service->service_name = $request->service_name;
        $saved = $service->save();
        if ($saved){
            return redirect()->route('admin.service.venueServiceList')->with('success','<b>'.ucfirst($request->service_name).'</b> service has been added');
        }else{
            return redirect()->route('admin.service.add-service')->with('fail','something went wrong, try again');
        }
    }

    public function editService(Request $request){
        $service_id = $request->id;
        $service = ServiceType::findOrFail($service_id);
        $data=[
            'pageTitle' => 'Edit Service',
            'service'=>$service
        ];
        return view('back.pages.admin.manage-service.edit-service',$data);
    }
    public function updateService(Request $request){
        $service_id = $request->service_id;
        $service = ServiceType::findOrFail($service_id);

        // validate
        $request->validate([
            'service_name' => 'required|min:5|unique:service_types,service_name,'.$service_id,
        ],[
            'service_name.required' => ':Atribute is required',
            'service_name.min' => ':Atribute must contains atleast 5 character',
            'service_name.unique' => 'This :attribute is already exists'
        ]);
        $service->service_name = $request->service_name;
        $service->service_slug = null;
        $saved = $service->save();
        if($saved){
            return redirect()->route('admin.service.venueServiceList',['id'=>$service_id])->with('success','<b>'.ucfirst($request->service_name).'</b> service has been updated');
        }else{
            return redirect()->route('admin.service.edit-service',['id'=>$service_id])->with('fail','Something went wrong, try again');

        }
    }
    public function deleteService(Request $request){
        $service_id = $request->id;
        $service = ServiceType::findOrFail($service_id);
        $service_name = $service->service_name;

        $service -> delete();
        return redirect()->route('admin.service.venueServiceList')->with('success','<b>'.ucfirst($service_name).'</b> Type of Service has deleted successfully');
    }

}
