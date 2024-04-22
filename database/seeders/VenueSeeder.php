<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VenueSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $data = [];
        DB::table('venues')->insert([
            [
                'name' => 'Studio Foto Ditangguhkan 1',
                'status' => 0,
                'address' => 'Pauh',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio foto di daerah padang',
                'phone_number' => '08923478723',
                'latitude' => -0.892292000,
                'longitude' => 100.327448000,
                'reject_note' => '',
                'owner_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Studio Foto Diterima 1',
                'status' => 1,
                'address' => 'Padang, Sumatera Barat',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio foto di daerah padang B',
                'phone_number' => '08923478753',
                'latitude' => -0.898336000,
                'longitude' => 100.355960000,
                'reject_note' => '',
                'owner_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Studio Foto Ditolak 1',
                'status' => 2,
                'address' => 'Padang, Sumatera Barat',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio foto di daerah padang B',
                'phone_number' => '08923478752',
                'latitude' => -0.875730000,
                'longitude' => 100.384948000,
                'reject_note' => 'tidak ada surat IMB',
                'owner_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Seed payment_method_details
        $paymentMethodDetailsData = [];
        $venuesCount = 3;
        for ($venueId = 1; $venueId <= $venuesCount; $venueId++) {
            for ($i = 0; $i < 8; $i++) {
                $paymentMethodDetailsData[] = [
                    'no_rek' => $this->generateRandomRekNumber(),
                    'venue_id' => $venueId,
                    'payment_method_id' => rand(1, 13),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        DB::table('payment_method_details')->insert($paymentMethodDetailsData);

        // Seed opening_hours
        $openingHoursData = [];
        for ($venueId = 1; $venueId <= $venuesCount; $venueId++) {
            $days = range(1, 7); // Array hari dalam seminggu (1 = Senin, 7 = Minggu)
            shuffle($days); // Acak urutan hari
            $daysToUse = array_slice($days, 0, rand(2, 5)); // Ambil 2-5 hari secara acak

            foreach ($daysToUse as $day) {
                for ($hour = 1; $hour <= 48; $hour++) {
                    $openingHoursData[] = [
                        'status' => rand(1, 2),
                        'venue_id' => $venueId,
                        'day_id' => $day,
                        'hour_id' => $hour,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }
        DB::table('opening_hours')->insert($openingHoursData);

        // Seed venue_images
        $venueImagesData = [];
        for ($venueId = 1; $venueId <= $venuesCount; $venueId++) {
            for ($i = 0; $i < rand(3, 5); $i++) {
                $imageNumber = rand(1, 7);
                $venueImagesData[] = [
                    'venue_id' => $venueId,
                    'image' => 'studio' . $imageNumber . '.jpg',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        DB::table('venue_images')->insert($venueImagesData);
    }
    private function generateRandomRekNumber()
    {
        // Generate 16 digit angka acak
        $rekNumber = '';
        for ($i = 0; $i < 16; $i++) {
            $rekNumber .= mt_rand(0, 9);
        }
        return $rekNumber;
    }
}
