<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class VenueSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('venues')->insert([
            [
            'name' => 'Studio Foto A',
            'status' => 0,
            'address' => 'Padang, Sumatera Barat',
            'imb' => '',
            'information' => 'Studio foto di daerah padang',
            'phone_number' => '08923478723',
            'latitude' => -0.892292000,
            'longitude' => 100.327448000,
            'reject_note' => '',
            'owner_id' => 1,
            ],
            [
            'name' => 'Studio Foto B',
            'status' => 1,
            'address' => 'Padang, Sumatera Barat',
            'imb' => '',
            'information' => 'Studio foto di daerah padang B',
            'phone_number' => '08923478753',
            'latitude' => -0.898336000,
            'longitude' => 100.355960000,
            'reject_note' => '',
            'owner_id' => 1,
            ],
            [
            'name' => 'Studio Foto B',
            'status' => 2,
            'address' => 'Padang, Sumatera Barat',
            'imb' => '',
            'information' => 'Studio foto di daerah padang B',
            'phone_number' => '08923478752',
            'latitude' => -0.875730000,
            'longitude' => 100.384948000,
            'reject_note' => 'tidak ada surat IMB',
            'owner_id' => 1,
            ],


        ]);
    }
}
