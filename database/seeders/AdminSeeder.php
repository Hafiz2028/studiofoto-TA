<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Customer;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
        'name'=>'superadmin',
        'username'=>'superadmin',
        'email'=>'admin@gmail.com',
        'password'=>Hash::make('admin'),
        'handphone'=>'090928312',
        'address'=>'123 main street',

        ]);
        Customer::create([
        'name'=>'supercustomer',
        'username'=>'supercustomer',
        'email'=>'customer@gmail.com',
        'password'=>Hash::make('admin'),
        'handphone'=>'02222222',
        'address'=>'123 cust street',

        ]);
        Owner::create([
        'name'=>'superowner',
        'username'=>'superowner',
        'email'=>'owner@gmail.com',
        'password'=>Hash::make('admin'),
        'handphone'=>'01111111',
        'ktp'=>'https://i.pinimg.com/originals/75/47/96/754796aee83e3a925a80abe18bb478a9.jpg',
        'address'=>'123 owner street',
        ]);
    }
}
