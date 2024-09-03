<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Owner;
use App\Models\Customer;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'superadmin',
            'username' => 'superadmin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'handphone' => '090928312',
            'role' => 'admin',
            'address' => '123 main street',

        ]);
        $customerUser = User::create([
            'name' => 'supercustomer',
            'username' => 'supercustomer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('admin'),
            'handphone' => '02222222',
            'address' => '123 cust street',
            'role' => 'customer',
        ]);
        Customer::create([
            'user_id' => $customerUser->id,
            'verified' => 1,
        ]);

        $ownerUsers = [
            [
                'name' => 'superowner',
                'username' => 'superowner',
                'email' => 'hafizauliarahmadoni@gmail.com',
                'handphone' => '089617702747',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Arisa Photo Owner',
                'username' => 'arisaowner',
                'email' => 'arisaowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Virgo Photo Owner',
                'username' => 'virgoowner',
                'email' => 'virgoowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Arena Photo Owner',
                'username' => 'arenaowner',
                'email' => 'arenaowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Raja Studio Owner',
                'username' => 'rajastudioowner',
                'email' => 'rajastudioowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Shafastudio owner',
                'username' => 'shafastudioowner',
                'email' => 'shafastudioowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Wasabi Studio Owner',
                'username' => 'wasabiowner',
                'email' => 'wasabiowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Rikoza Studio owner',
                'username' => 'rikozaowner',
                'email' => 'rikozaowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'family Foto Studio Owner',
                'username' => 'familyfotoowner',
                'email' => 'familyfotoowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Rens Studio Owner',
                'username' => 'rensstudioowner',
                'email' => 'rensstudioowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'One Smile Owner',
                'username' => 'onesmileowner',
                'email' => 'onesmileowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Rara Photo Owner',
                'username' => 'raraphotoowner',
                'email' => 'raraphotoowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Studio 59 Owner',
                'username' => 'studio59owner',
                'email' => 'studio59owner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'SUA Studio owner',
                'username' => 'suastudioowner',
                'email' => 'suastudioowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Emily Queen',
                'username' => 'emilyqueenowner',
                'email' => 'emilyqueenowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'CEO Photo Owner',
                'username' => 'ceophotoowner',
                'email' => 'ceophotoowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Dimension Owner',
                'username' => 'dimensionowner',
                'email' => 'dimensionowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'MD STUDIO Owner',
                'username' => 'mdstudioowner',
                'email' => 'mdstudioowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Redja Studio Owner',
                'username' => 'redjastudioowner',
                'email' => 'redjastudioowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Analogy Visual Owner',
                'username' => 'analogyvisualowner',
                'email' => 'analogyvisualowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Andalas Photo Owner',
                'username' => 'andalasphotoowner',
                'email' => 'andalasphotoowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Siteba Photo Owner',
                'username' => 'sitebaphotoowner',
                'email' => 'sitebaphotoowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Semute Studio Owner',
                'username' => 'semuteowner',
                'email' => 'semuteowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Kharismatik Foto Owner',
                'username' => 'kharismatikowner',
                'email' => 'kharismatikowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Leica Home Owner',
                'username' => 'leicahomeowner',
                'email' => 'leicahomeowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Evolvet Studio Owner',
                'username' => 'evolvetowner',
                'email' => 'evolvetowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
            [
                'name' => 'Asean Photo Owner',
                'username' => 'aseanphotoowner',
                'email' => 'aseanphotoowner@gmail.com',
                'handphone' => '08234293423',
                'ktp' => 'ktp1.jpg',
                'address' => 'Padang',
                'verified' => 1,
            ],
        ];

        foreach ($ownerUsers as $ownerData) {
            $user = User::create([
                'name' => $ownerData['name'],
                'username' => $ownerData['username'],
                'email' => $ownerData['email'],
                'password' => Hash::make('admin'),
                'handphone' => $ownerData['handphone'],
                'address' => $ownerData['address'],
                'role' => 'owner',
            ]);

            Owner::create([
                'user_id' => $user->id,
                'ktp' => $ownerData['ktp'],
                'verified' => $ownerData['verified'],
            ]);
        }
    }
}
