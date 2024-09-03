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
                'name' => 'Studio Foto Diproses',
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
                'name' => 'Studio Foto Diterima',
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
                'name' => 'Studio Foto Ditolak',
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
            [
                'name' => 'Arisa Photo',
                'status' => 1,
                'address' => 'Jalan Dr. Moh Hatta Simpang Koto Tuo',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371100005",
                'map_link' => "https://maps.app.goo.gl/qkHhmXDWp3wto5hW7",
                'reject_note' => '',
                'owner_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Virgo Photo Studio',
                'status' => 1,
                'address' => 'Jl. Dr. Moh. Hatta',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371100008",
                'map_link' => "https://maps.app.goo.gl/KDvje4eQdsSVyUr18",
                'reject_note' => '',
                'owner_id' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Arena Photo',
                'status' => 1,
                'address' => 'Jalan m hatta no 10 simpang pasie',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371100005",
                'map_link' => "https://maps.app.goo.gl/xWRm9JBztBw5s9UN7",
                'reject_note' => '',
                'owner_id' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Raja Studio Photograpghy',
                'status' => 1,
                'address' => 'Dr. Moh. Hatta No.1, RT.01/RW.02',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371100008",
                'map_link' => "https://maps.app.goo.gl/HWQ7gn2De4sKWerW7",
                'reject_note' => '',
                'owner_id' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Shafastudio Indonesia',
                'status' => 1,
                'address' => 'Jl. Dr. Moh. Hatta No.3',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371100004",
                'map_link' => "https://maps.app.goo.gl/dcGNCqGKKXsS7jkj8",
                'reject_note' => '',
                'owner_id' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Wasabi Studio',
                'status' => 1,
                'address' => 'Jalan Dr M Hatta no 15',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371100002",
                'map_link' => "https://maps.app.goo.gl/DV4kz1LskAxYmquC7",
                'reject_note' => '',
                'owner_id' => 7,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Rikoza Studio',
                'status' => 1,
                'address' => 'Jl. Piai Tangah',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371100003",
                'map_link' => "https://maps.app.goo.gl/4HSJkaL4sCe1p13j9",
                'reject_note' => '',
                'owner_id' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'family Foto Studio',
                'status' => 1,
                'address' => 'Jl. Raya Kuranji',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371090007",
                'map_link' => "https://maps.app.goo.gl/hAQMquEQ6igmQ3ny5",
                'reject_note' => '',
                'owner_id' => 9,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Rens Studio Photography',
                'status' => 1,
                'address' => 'Jl. By Pass Jl. Ketaping No.16, RT.001/RW.005',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371090002",
                'map_link' => "https://maps.app.goo.gl/PwS9jZcDeDuPfNoF6",
                'reject_note' => '',
                'owner_id' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'One Smile Studio',
                'status' => 1,
                'address' => 'Jl. By Pass Kayu Gadang',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371090002",
                'map_link' => "https://maps.app.goo.gl/gi9oeg1KB5TA2FEz7",
                'reject_note' => '',
                'owner_id' => 11,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Rara Photo Studio',
                'status' => 1,
                'address' => 'Jl. Dr. Moh. Hatta No.30',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371090002",
                'map_link' => "https://maps.app.goo.gl/cPgUhEwG8B5YDncB8",
                'reject_note' => '',
                'owner_id' => 12,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Studio 59 Photography',
                'status' => 1,
                'address' => 'Jln. M. Yunus No 32, Sarang Gagak Lubuk lintah',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371090001",
                'map_link' => "https://maps.app.goo.gl/JuBE7EvdURc7kcVT7",
                'reject_note' => '',
                'owner_id' => 13,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'SUA Studio',
                'status' => 1,
                'address' => 'Jl. Dr. Sutomo No.1',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371050033",
                'map_link' => "https://maps.app.goo.gl/veHVWdtpRfCGHxPx8",
                'reject_note' => '',
                'owner_id' => 14,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Emily Queen Home Photo Studio',
                'status' => 1,
                'address' => 'Jl. Sawahan V No.1',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371050020",
                'map_link' => "https://maps.app.goo.gl/Mwj3AhowVcgt2Lwy9",
                'reject_note' => '',
                'owner_id' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ceo Photo Studio',
                'status' => 1,
                'address' => 'Jl. Gajah Mada Dalam G No.18',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371080003",
                'map_link' => "https://maps.app.goo.gl/DgohUAxXXa5c1ox1A",
                'reject_note' => '',
                'owner_id' => 16,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'DIMENSION PHOTOGRAPHY PADANG',
                'status' => 1,
                'address' => 'Jl. Pagang Raya No.35',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371080007",
                'map_link' => "https://maps.app.goo.gl/eE7UvfwRZFu678hU9",
                'reject_note' => '',
                'owner_id' => 17,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'MD STUDIO PADANG',
                'status' => 1,
                'address' => 'Jl. Prof. Hamka No.234',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371070017",
                'map_link' => "https://maps.app.goo.gl/91qjrjiwWWKisjXk6",
                'reject_note' => '',
                'owner_id' => 18,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'REDJA STUDIO PHOTOGRAPHY',
                'status' => 1,
                'address' => 'Jl. Jedah',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371110025",
                'map_link' => "https://maps.app.goo.gl/ht44orHGAjhXKDUw8",
                'reject_note' => '',
                'owner_id' => 19,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Analogy Visual Studio',
                'status' => 1,
                'address' => 'Jl. Dr. Moh. Hatta No.24',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371090002",
                'map_link' => "https://maps.app.goo.gl/ji4349ARLP32FidU7",
                'reject_note' => '',
                'owner_id' => 20,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Andalas Photo',
                'status' => 1,
                'address' => 'dpn bank BRI, Blk. A Jl. Andalas No.94',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371050032",
                'map_link' => "https://maps.app.goo.gl/nSvujiiHcgSHfKyA6",
                'reject_note' => '',
                'owner_id' => 21,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Siteba Photo Studio',
                'status' => 1,
                'address' => 'No.1D Pondok Kopi depan yamaha siteba, Jl. Raya Siteba',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371080006",
                'map_link' => "https://maps.app.goo.gl/1RfizTjpb59go3958",
                'reject_note' => '',
                'owner_id' => 22,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Semute Studio',
                'status' => 1,
                'address' => 'Siteba poltekes, Jl. Pd. Kopi',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371080006",
                'map_link' => "https://maps.app.goo.gl/gFbiSuy1Jp2XCF6y8",
                'reject_note' => '',
                'owner_id' => 23,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Kharismatik Foto Padang',
                'status' => 1,
                'address' => 'Jl. Kp. Kalawi No.8',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371090003",
                'map_link' => "https://maps.app.goo.gl/2No5dx5CwxCqfm7j8",
                'reject_note' => '',
                'owner_id' => 24,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Leica Home Studio',
                'status' => 1,
                'address' => 'Jl. Batang Lembang No.9',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371070019",
                'map_link' => "https://maps.app.goo.gl/jyhH85Py7Evu4kNc8",
                'reject_note' => '',
                'owner_id' => 25,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Evolvet Studio',
                'status' => 1,
                'address' => 'Jl. Palembang No.11',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371070010",
                'map_link' => "https://maps.app.goo.gl/o6m9ndS27rXiSCN2A",
                'reject_note' => '',
                'owner_id' => 26,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Asean Photo Studio',
                'status' => 1,
                'address' => 'Jl. Prof. Dr. Hamka No.121',
                'imb' => 'contoh_imb.pdf',
                'information' => 'Studio Photo yang memiliki berbebagai macam paket foto',
                'phone_number' => '08923478753',
                'village_id' => "1371070018",
                'map_link' => "https://maps.app.goo.gl/puh7B5ntyWLfHV168",
                'reject_note' => '',
                'owner_id' => 27,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Seed payment_method_details
        $paymentMethodDetailsData = [];
        $venuesCount = 29;
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
                    $status = ($hour >= 16 && $hour <= 45) ? 2 : 1;
                    $openingHoursData[] = [
                        'status' => $status,
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

        // Seed service_packages
        $servicePackagesData = [];
        $servicePackageDetailsData = [];
        $addOnPackageDetailsData = [];
        $framePhotoDetailsData = [];
        $printPhotoDetailsData = [];
        $serviceEventIds = DB::table('service_events')->pluck('id')->toArray(); // Get all service_event IDs

        foreach ($serviceEventIds as $serviceEventId) {
            $packageCount = rand(2, 4); // Random number of packages per event

            for ($i = 0; $i < $packageCount; $i++) {
                $servicePackage = [
                    'name' => 'Paket ' . ($i + 1),
                    'information' => 'Deskripsi Paket ' . ($i + 1),
                    'dp_status' => 2,
                    'dp_percentage' => null, // Random percentage between 10% and 50%
                    'dp_min' => rand(100000, 500000), // Random DP minimum
                    'service_event_id' => $serviceEventId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $servicePackageId = DB::table('service_packages')->insertGetId($servicePackage);

                // Seed service_package_details for each package
                for ($j = 0; $j < rand(1, 3); $j++) {
                    $servicePackageDetailsData[] = [
                        'sum_person' => rand(1, 10),
                        'time_status' => rand(0, 3),
                        'price' => rand(500000, 2000000), // Random price
                        'service_package_id' => $servicePackageId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // Seed add_on_package_details
                for ($k = 0; $k < rand(1, 3); $k++) {
                    $addOnPackageId = rand(1, 4); // Assuming you have 5 add-on packages
                    $addOnPackageDetailsData[] = [
                        'service_package_id' => $servicePackageId,
                        'add_on_package_id' => $addOnPackageId,
                        'sum' => rand(1, 2),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // Seed frame_photo_details
                for ($l = 0; $l < rand(1, 2); $l++) {
                    $framePhotoDetailsData[] = [
                        'service_package_id' => $servicePackageId,
                        'print_photo_id' => rand(1, 2), // Assuming you have 5 print photo sizes
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                for ($l = 0; $l < rand(1, 2); $l++) {
                    $printPhotoDetailsData[] = [
                        'service_package_id' => $servicePackageId,
                        'print_photo_id' => rand(1, 2), // Assuming you have 5 print photo sizes
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }
        DB::table('service_package_details')->insert($servicePackageDetailsData);
        DB::table('add_on_package_details')->insert($addOnPackageDetailsData);
        DB::table('frame_photo_details')->insert($framePhotoDetailsData);
        DB::table('print_photo_details')->insert($printPhotoDetailsData);
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
