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
            'name' => 'superadmin',
            'username' => 'superadmin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'handphone' => '090928312',
            'address' => '123 main street',

        ]);
        Customer::create([
            'name' => 'supercustomer',
            'username' => 'supercustomer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('admin'),
            'handphone' => '02222222',
            'address' => '123 cust street',
            'verified' => 1,

        ]);
        Owner::create(
            [
                'id' => 1,
                'name' => 'superowner',
                'username' => 'superowner',
                'email' => 'owner@gmail.com',
                'password' => Hash::make('admin'),
                'handphone' => '01111111',
                'ktp' => '',
                'address' => '123 owner street',
                'verified' => 1,
            ]
        );
        Owner::create(
            [
                'id' => 2,
                'name' => 'Aldi',
                'username' => 'Aldirianto',
                'email' => 'owner2@gmail.com',
                'password' => Hash::make('admin'),
                'handphone' => '011111112343',
                'ktp' => 'ktp1.jpg',
                'address' => '123 owner street',
                'verified' => 1,
            ]
        );
        Owner::create(
            [
                'id' => 3,
                'name' => 'Anto',
                'username' => 'Antoriadi',
                'email' => 'owner3@gmail.com',
                'password' => Hash::make('admin'),
                'handphone' => '011111112343',
                'ktp' => '',
                'address' => '123 owner street',
                'verified' => 1,
            ]
        );
    }
}
