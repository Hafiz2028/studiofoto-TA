<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\District;
use App\Models\Village;


class MasterSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('days')->insert([
            [
                'name' => 'Senin',
                'slug' => 'Monday'
            ],
            [
                'name' => 'Selasa',
                'slug' => 'Tuesday'
            ],
            [
                'name' => 'Rabu',
                'slug' => 'Wednesday'
            ],
            [
                'name' => 'Kamis',
                'slug' => 'Thursday'
            ],
            [
                'name' => 'Jumat',
                'slug' => 'Friday'
            ],
            [
                'name' => 'Sabtu',
                'slug' => 'Saturday'
            ],
            [
                'name' => 'Minggu',
                'slug' => 'Sunday'
            ]
        ]);
        DB::table('hours')->insert([
            [
                'hour' => '00.00',
            ],
            [
                'hour' => '00.30'
            ],
            [
                'hour' => '01.00'
            ],
            [
                'hour' => '01.30'
            ],
            [
                'hour' => '02.00'
            ],
            [
                'hour' => '02.30'
            ],
            [
                'hour' => '03.00'
            ],
            [
                'hour' => '03.30'
            ],
            [
                'hour' => '04.00'
            ],
            [
                'hour' => '04.30'
            ],
            [
                'hour' => '05.00'
            ],
            [
                'hour' => '05.30'
            ],
            [
                'hour' => '06.00'
            ],
            [
                'hour' => '06.30'
            ],
            [
                'hour' => '07.00'
            ],
            [
                'hour' => '07.30'
            ],
            [
                'hour' => '08.00'
            ],
            [
                'hour' => '08.30'
            ],
            [
                'hour' => '09.00'
            ],
            [
                'hour' => '09.30'
            ],
            [
                'hour' => '10.00'
            ],
            [
                'hour' => '10.30'
            ],
            [
                'hour' => '11.00'
            ],
            [
                'hour' => '11.30'
            ],
            [
                'hour' => '12.00'
            ],
            [
                'hour' => '12.30'
            ],
            [
                'hour' => '13.00'
            ],
            [
                'hour' => '13.30'
            ],
            [
                'hour' => '14.00'
            ],
            [
                'hour' => '14.30'
            ],
            [
                'hour' => '15.00'
            ],
            [
                'hour' => '15.30'
            ],
            [
                'hour' => '16.00'
            ],
            [
                'hour' => '16.30'
            ],
            [
                'hour' => '17.00'
            ],
            [
                'hour' => '17.30'
            ],
            [
                'hour' => '18.00'
            ],
            [
                'hour' => '18.30'
            ],
            [
                'hour' => '19.00'
            ],
            [
                'hour' => '19.30'
            ],
            [
                'hour' => '20.00'
            ],
            [
                'hour' => '20.30'
            ],
            [
                'hour' => '21.00'
            ],
            [
                'hour' => '21.30'
            ],
            [
                'hour' => '22.00'
            ],
            [
                'hour' => '22.30'
            ],
            [
                'hour' => '23.00'
            ],
            [
                'hour' => '23.30'
            ]
        ]);
        DB::table('payment_methods')->insert([
            [
                'name' => 'Bank BNI',
                'icon' => 'bni.png',
            ],
            [
                'name' => 'Bank Mandiri',
                'icon' => 'mandiri.png',
            ],
            [
                'name' => 'Bank BRI',
                'icon' => 'bri.png',
            ],
            [
                'name' => 'Bank BCA',
                'icon' => 'bca.png',
            ],
            [
                'name' => 'Bank Bukopin',
                'icon' => 'bukopin.png',
            ],
            [
                'name' => 'Bank Permata',
                'icon' => 'permata.png',
            ],
            [
                'name' => 'Bank BTN',
                'icon' => 'btn.png',
            ],
            [
                'name' => 'Bank BJB',
                'icon' => 'bjb.png',
            ],
            [
                'name' => 'Bank CIMB',
                'icon' => 'cimb.png',
            ],
            [
                'name' => 'Bank BSI',
                'icon' => 'bsi.png',
            ],
            [
                'name' => 'Dana',
                'icon' => 'dana.png',
            ],
            [
                'name' => 'Shopee Pay',
                'icon' => 'shopeepay.png',
            ],
            [
                'name' => 'Gopay',
                'icon' => 'gopay.png',
            ],
        ]);
        DB::table('service_types')->insert([
            [
                'service_name' => 'Wisuda',
                'service_slug' => 'wisuda'
            ],
            [
                'service_name' => 'Self Photo',
                'service_slug' => 'self-photo'
            ],
            [
                'service_name' => 'Keluarga',
                'service_slug' => 'keluarga'
            ],
            [
                'service_name' => 'Kelompok',
                'service_slug' => 'kelompok'
            ],
            [
                'service_name' => 'Pre-Wedding',
                'service_slug' => 'pre-wedding'
            ],
            [
                'service_name' => 'Couple',
                'service_slug' => 'couple'
            ]

        ]);
        DB::table('print_photos')->insert([
            [
                'size' => '2x3'
            ],
            [
                'size' => '3x4'
            ],
            [
                'size' => '4x6'
            ],
            [
                'size' => '1R'
            ],
            [
                'size' => '2R'
            ],
            [
                'size' => '3R'
            ],
            [
                'size' => '4R'
            ],
            [
                'size' => '5R'
            ],
            [
                'size' => '6R'
            ],
            [
                'size' => '7R'
            ],
            [
                'size' => '8R'
            ],
            [
                'size' => '9R'
            ],
            [
                'size' => '10R'
            ],
            [
                'size' => '8R Jbo'
            ],
            [
                'size' => '11R'
            ],
            [
                'size' => '12R'
            ],
            [
                'size' => '12R Jbo'
            ],
            [
                'size' => '16R'
            ],
            [
                'size' => '20R'
            ],
            [
                'size' => '22R'
            ],
            [
                'size' => '24R'
            ],
            [
                'size' => '30R'
            ],
            [
                'size' => 'A4'
            ],
            [
                'size' => 'A3'
            ],
            [
                'size' => 'B5'
            ],
            [
                'size' => 'B4'
            ],
            [
                'size' => 'B3'
            ],


        ]);
        DB::table('add_on_packages')->insert([
            [
                'name' => 'Moment'
            ],
            [
                'name' => 'Outfit'
            ],

        ]);
        $regencyId = 1371;
        $response = Http::get("https://apiwilayah.metrosoftware.id/api-wilayah-indonesia/api/districts/{$regencyId}.json");
        $districts = $response->json();

        foreach ($districts as $district) {
            District::create([
                'id' => $district['id'],
                'name' => $district['name'],
            ]);
        }

        $districts = District::all();
        foreach ($districts as $district) {
            $districtId = $district->id;

            $response = Http::get("https://apiwilayah.metrosoftware.id/api-wilayah-indonesia/api/villages/{$districtId}.json");
            $villages = $response->json();

            foreach ($villages as $village) {
                Village::create([
                    'id' => $village['id'],
                    'district_id' => $districtId,
                    'name' => $village['name'],
                ]);
            }
        }
    }
}
