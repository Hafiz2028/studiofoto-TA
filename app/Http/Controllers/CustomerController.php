<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use constGuards;
use constDefaults;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customer = Customer::all();
        return view('back.pages.admin.manage-users.customer.cust-list', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data=[
            'pageTitle' => 'Add Customer',
        ];
        return view('back.pages.admin.manage-users.customer.add-cust',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:5|unique:customers,name',
            'username' => 'required|min:5|unique:customers,username|regex:/^\S*$/',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|min:5|max:45',
            'handphone' => 'required|min:9|max:15',
            'address' => 'required|string|max:255',
            'password_confirmation' => 'required|min:5|max:45|same:password',
        ],[
            'name.required' => ':Attribute is required.',
            'name.min' => ':Attribute must be at least 5 characters.',
            'name.unique' => ':Attribute has already been taken.',
            'username.regex' => ':Attribute may only contain letters, numbers, and underscores.',
            'username.max' => ':Attribute must not exceed 255 characters.',
            'username.required' => ':Attribute is required.',
            'username.min' => ':Attribute must be at least 5 characters.',
            'username.unique' => ':Attribute has already been taken.',
            'email.required' => ':Attribute is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => ':Attribute has already been taken.',
            'password.required' => ':Attribute is required.',
            'password.min' => ':Attribute must be at least 5 characters.',
            'password.max' => ':Attribute may not be greater than 45 characters.',
            'handphone.required' => ':Attribute field is required.',
            'password_confirmation.required' => ':Attribute is required.',
            'password_confirmation.min' => ':Attribute must be at least 5 characters.',
            'password_confirmation.max' => ':Attribute may not be greater than 45 characters.',
            'handphone.required' => ':Attribute field is required.',
            'handphone.min' => ':Attribute must be at least 9 characters.',
            'handphone.max' => ':Attribute may not be greater than 15 characters.',
            'address.required' => ':Attribute is required.',
            'address.string' => ':Attribute must be a string.',
            'address.max' => ':Attribute may not be greater than 255 characters.',
        ]);
        $customer = new Customer();

        $customer->name = $validatedData['name'];
        $customer->username = $validatedData['username'];
        $customer->email = $validatedData['email'];
        $customer->password = Hash::make($validatedData['password']);
        $customer->handphone = $validatedData['handphone'];
        $customer->address = $validatedData['address'];
        $saved = $customer->save();

        if($saved){
            return redirect()->route('admin.user.customer.index')->with('success','<b>'.ucfirst($validatedData['name']).'</b> user has been added');
        }else{
            return redirect()->route('admin.user.customer.create')->with('fail','something went wrong, try again');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer_id = $id;
        $customer = Customer::findOrFail($customer_id);
        $data =[
            'pageTitle' => 'Edit Customer User',
            'customer' =>$customer
        ];
        return view('back.pages.admin.manage-users.customer.edit-cust',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer_id = $id;
        $customer = Customer::findOrFail($customer_id);

        $request->validate([
            'name' => 'required|min:5|unique:customers,name,'.$customer_id,
            'username' => 'required|min:5|unique:customers,username,'.$customer_id.'|regex:/^\S*$/',
            'handphone' => 'required|min:9|max:15',
            'address' => 'required|string|max:255',
        ],[
            'name.required' => ':Attribute is required.',
            'name.min' => ':Attribute must be at least 5 characters.',
            'name.unique' => ':Attribute has already been taken.',
            'username.regex' => ':Attribute may only contain letters, numbers, and underscores.',
            'username.required' => ':Attribute is required.',
            'username.min' => ':Attribute must be at least 5 characters.',
            'username.unique' => ':Attribute has already been taken.',
            'handphone.required' => ':Attribute field is required.',
            'handphone.required' => ':Attribute field is required.',
            'handphone.min' => ':Attribute must be at least 9 characters.',
            'handphone.max' => ':Attribute may not be greater than 15 characters.',
            'address.required' => ':Attribute is required.',
            'address.string' => ':Attribute must be a string.',
            'address.max' => ':Attribute may not be greater than 255 characters.',
        ]);
        $customer->name = $request->name;
        $customer->username = $request->username;
        $customer->handphone = $request->handphone;
        $customer->address = $request->address;
        $saved= $customer->save();
        if($saved){
            return redirect()->route('admin.user.customer.index',['id'=>$customer_id])->with('success','User <b>'.ucfirst($request->name).'</b> has been updated');
        }else{
            return redirect()->route('admin.user.customer.edit',['id'=>$customer_id])->with('fail','Something went wrong, try again');

        }
    }
    public function destroy(string $id)
    {
        $customer_id = $id;
        $customer = Customer::findOrFail($customer_id);
        $customer_name = $customer->name;

        $delete = $customer -> delete();
        if($delete){
        return redirect()->route('admin.user.customer.index')->with('success','User <b>'.ucfirst($customer_name).'</b> deleted successfully');
        }else{
        return redirect()->route('admin.user.customer.index')->with('fail',"User <b>".ucfirst($customer_name)."</b> can't deleted, Try again");
        }
    }
}
