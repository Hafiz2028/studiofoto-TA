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
                'village_id' => "1371100003",
                'map_link' => "https://www.google.com/maps/@-0.930144,100.4215634,13.07z?entry=ttu",
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
                'village_id' => "1371100004",
                'map_link' => "https://www.google.com/maps/@-0.930144,100.4215634,13z?entry=ttu",
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
                'village_id' => "1371100008",
                'map_link' => "https://www.google.com/maps/@-0.930144,100.4215634,13z?entry=ttu",
                'reject_note' => 'tidak ada surat IMB',
                'owner_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Seed payment_method_details
        $paymentMethodDetailsData = [];
        $venuesCount = 3;
        $usedPaymentMethods = [];

        for ($venueId = 1; $venueId <= $venuesCount; $venueId++) {
            $usedPaymentMethods[$venueId] = [];
            for ($i = 0; $i < 8; $i++) {
                $paymentMethodId = rand(1, 13);
                while (in_array($paymentMethodId, $usedPaymentMethods[$venueId])) {
                    $paymentMethodId = rand(1, 13);
                }
                $usedPaymentMethods[$venueId][] = $paymentMethodId;
                $paymentMethodDetailsData[] = [
                    'no_rek' => $this->generateRandomRekNumber(),
                    'venue_id' => $venueId,
                    'payment_method_id' => $paymentMethodId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        DB::table('payment_method_details')->insert($paymentMethodDetailsData);

        // Seed opening_hours
        $openingHoursData = [];
        for ($venueId = 1; $venueId <= $venuesCount; $venueId++) {
            $days = range(1, 7);
            shuffle($days);
            $daysToUse = array_slice($days, 0, rand(2, 5));
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
            $usedImages = [];

            for ($i = 0; $i < rand(3, 5); $i++) {
                $imageNumber = rand(1, 7);
                while (in_array($imageNumber, $usedImages)) {
                    $imageNumber = rand(1, 7);
                }
                $usedImages[] = $imageNumber;
                $venueImagesData[] = [
                    'venue_id' => $venueId,
                    'image' => 'studio' . $imageNumber . '.jpg',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('venue_images')->insert($venueImagesData);

        // seeder service_event
        $serviceEventsData = [];
        $serviceEventImagesData = [];
        $loremIpsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempor justo quis enim eleifend convallis. Nullam ut justo eu sem cursus pulvinar. Maecenas mattis risus sed orci pulvinar, non tempus ipsum commodo. Sed eget mi eu est aliquam vehicula.";
        $venuesWithStatusOne = DB::table('venues')->where('status', 1)->pluck('id')->toArray();

        foreach ($venuesWithStatusOne as $venueId) {
            $usedServiceTypes = [];
            $serviceTypeId = 1;
            for ($i = 0; $i < rand(3, 4); $i++) {
                $serviceName = '';
                switch ($serviceTypeId) {
                    case 1:
                        $serviceName = 'Wisuda';
                        break;
                    case 2:
                        $serviceName = 'Self Photo';
                        break;
                    case 3:
                        $serviceName = 'Keluarga';
                        break;
                    case 4:
                        $serviceName = 'Organisasi';
                        break;
                    case 5:
                        $serviceName = 'Pre-Wedding';
                        break;
                    case 6:
                        $serviceName = 'Couple';
                        break;
                }
                $catalog = 'paket' . rand(1, 6) . '.jpg';
                $serviceEvent = [
                    'name' => 'Foto ' . $serviceName,
                    'catalog' => $catalog,
                    'description' => $loremIpsum,
                    'venue_id' => $venueId,
                    'service_type_id' => $serviceTypeId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $serviceEventId = DB::table('service_events')->insertGetId($serviceEvent);

                // Store service_event_images data
                $usedImages = [];
                for ($j = 0; $j < rand(4, 5); $j++) {
                    $imageName = 'studio' . rand(1, 7) . '.jpg';
                    while (in_array($imageName, $usedImages)) {
                        $imageName = 'studio' . rand(1, 7) . '.jpg';
                    }
                    $usedImages[] = $imageName;
                    $serviceEventImagesData[] = [
                        'service_event_id' => $serviceEventId,
                        'image' => $imageName,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                $serviceTypeId++;
                if ($serviceTypeId > 6) {
                    $serviceTypeId = 1;
                }
            }
        }
        DB::table('service_event_images')->insert($serviceEventImagesData);
    }


    private function generateRandomRekNumber()
    {
        $rekNumber = '';
        for ($i = 0; $i < 16; $i++) {
            $rekNumber .= mt_rand(0, 9);
        }
        return $rekNumber;
    }
}
