<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Hour;
use App\Models\Rent;
use App\Models\Venue;
use App\Models\RentDetail;
use App\Models\OpeningHour;
use App\Models\RentPayment;
use App\Models\ServiceEvent;
use Illuminate\Http\Request;
use App\models\ServicePackage;
use App\Models\PrintPhotoDetail;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethodDetail;
use Illuminate\Support\Facades\Log;
use App\Models\ServicePackageDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $selectedRentId = $request->input('selectedRentId', null);
        $ownerId = Auth::guard('owner')->id();
        $venues = Venue::where('owner_id', $ownerId)->where('status', 1)->get();
        if ($venues->isEmpty()) {
            return back()->with('error', 'Tidak ada venue yang terdaftar, daftarkan sekarang!!');
        }
        $venueIds = $venues->pluck('id')->toArray();
        $openingHours = OpeningHour::with('day', 'hour')->whereIn('venue_id', $venueIds)->get();
        $uniqueDayIds = collect($openingHours)->unique('day_id')->pluck('day_id')->toArray();
        $today = now()->format('Y-m-d');
        $services = ServiceEvent::with('serviceType')->whereIn('venue_id', $venueIds)->get();
        if ($services->isEmpty()) {
            return back()->with('error', 'Belum ada layanan pada venue yang telah disetujui, tambahkan sekarang!!');
        }
        $serviceEventIds = $services->pluck('id')->toArray();
        $packages = ServicePackage::with('printPhotoDetails.printServiceEvent.printPhoto')->whereIn('service_event_id', $serviceEventIds)->get();
        if ($packages->isEmpty()) {
            return back()->with('error', 'Belum Membuat Paket Foto pada Layanan, tambahkan sekarang!!');
        }
        $packageDetails = ServicePackageDetail::whereIn('service_package_id', $packages->pluck('id'))->get();
        $rents = Rent::with(['rentDetails.openingHour.hour'])->whereIn('service_package_detail_id', $packageDetails->pluck('id'))->get();
        foreach ($rents as $rent) {
            $openingHourIds = $rent->rentDetails->pluck('opening_hour_id');
            if ($openingHourIds->isNotEmpty()) {
                $firstOpeningHourId = $openingHourIds->first();
                $lastOpeningHourId = $openingHourIds->last();
                $firstOpeningHour = OpeningHour::find($firstOpeningHourId)->hour;
                $lastOpeningHour = OpeningHour::find($lastOpeningHourId)->hour;
                $nextHour = Hour::where('id', $lastOpeningHour->id + 1)->first();
                if ($nextHour) {
                    $rent->formatted_schedule = $firstOpeningHour->hour . ' - ' . $nextHour->hour;
                } else {
                    $rent->formatted_schedule = $firstOpeningHour->hour . ' - ' . $lastOpeningHour->hour;
                }
            } else {
                $rent->formatted_schedule = null;
            }
        }
        $bookDates = RentDetail::with('rent')
            ->whereHas('rent.servicePackageDetail.servicePackage.serviceEvent', function ($query) use ($venueIds) {
                $query->whereIn('venue_id', $venueIds);
            })
            ->whereHas('rent', function ($query) {
                $query->whereIn('rent_status', [0, 1, 5, 6]);
            })
            ->where('rent_id', '!=', $selectedRentId)
            ->get()
            ->map(function ($rentDetail) {
                return [
                    'rent_id' => $rentDetail->rent_id,
                    'opening_hour_id' => $rentDetail->opening_hour_id,
                    'date' => $rentDetail->rent->date,
                ];
            })
            ->toArray();

        $data = [
            'pageTitle' => 'Booking',
            'venues' => $venues,
            'uniqueDayIds' => $uniqueDayIds,
            'openingHours' => $openingHours,
            'today' => $today,
            'services' => $services,
            'packages' => $packages,
            'packageDetails' => $packageDetails,
            'rents' => $rents,
            'bookDates' => $bookDates,
        ];
        return view('back.pages.owner.booking-manage.index', $data);
    }

    public function updateStatus(Request $request)
    {

        $cutoffTime = Carbon::now()->setSeconds(0);
        $rents = Rent::whereIn('rent_status', [1, 5, 0])->get();
        $expiredRents = [];
        foreach ($rents as $rent) {
            $rentExpired = false;
            $rentDetails = RentDetail::where('rent_id', $rent->id)->get();

            if ($rentDetails->isNotEmpty()) {
                $openingHours = [];
                foreach ($rentDetails as $rentDetail) {
                    $openingHour = OpeningHour::find($rentDetail->opening_hour_id);
                    if ($openingHour) {
                        $openingHours[] = $openingHour;
                    }
                }
                $lastOpeningHour = end($openingHours);
                if ($lastOpeningHour) {
                    $hourParts = explode('.', $lastOpeningHour->hour->hour);
                    $hour = intval($hourParts[0]);
                    $minute = intval($hourParts[1]);
                    $scheduleTime = Carbon::createFromFormat('Y-m-d H:i', $rent->date . ' ' . sprintf('%02d:%02d', $hour, $minute));
                    $scheduleTime->addMinutes(30);
                    if ($scheduleTime < $cutoffTime) {
                        $rentExpired = true;
                    }
                }
            }
            if ($rentExpired) {
                $rent->rent_status = 4;
                $rent->save();
                $expiredRents[] = ['faktur' => $rent->faktur, 'name' => $rent->name];
            }
        }

        if (count($expiredRents) > 0) {
            $message = [];
            $message[] = 'Terdapat Jadwal Expired:';
            foreach ($expiredRents as $rent) {
                $message[] = "(Faktur: {$rent['faktur']}, Nama Cust: {$rent['name']})";
            }
            $message[] = 'Jika ada booking yang expired, jadwal tidak bisa diperbaharui.';
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'openingHour' => $openingHour->toArray(),
                'scheduleTime' => $scheduleTime->toArray(),
                'cutoffTime' => $cutoffTime->toArray()
            ]);
        } else {
            return response()->json([
                'status' => 'info',
                'message' => 'Tidak ada Jadwal Expired',
                'openingHour' => $openingHour->toArray(),
                'cutoffTime' => $cutoffTime->toArray()
            ]);
        }
    }

    public function show(string $id)
    {
        $rent = Rent::findOrFail($id);

        $data = [
            'rent' => $rent,
        ];

        return view('back.pages.owner.booking-manage.show', $data);
    }
    public function edit(string $id)
    {
        $ownerId = Auth::guard('owner')->id();
        $venues = Venue::where('owner_id', $ownerId)->where('status', 1)->get();
        if ($venues->isEmpty()) {
            return back()->with('error', 'Tidak ada venue yang terdaftar, daftarkan sekarang!!');
        }
        $venueIds = $venues->pluck('id')->toArray();
        $openingHours = OpeningHour::with('day', 'hour')->whereIn('venue_id', $venueIds)->get();
        $uniqueDayIds = collect($openingHours)->unique('day_id')->pluck('day_id')->toArray();
        $today = now()->format('Y-m-d');
        $services = ServiceEvent::with('serviceType')->whereIn('venue_id', $venueIds)->get();
        if ($services->isEmpty()) {
            return back()->with('error', 'Belum ada layanan pada venue yang telah disetujui, tambahkan sekarang!!');
        }
        $serviceEventIds = $services->pluck('id')->toArray();
        $packages = ServicePackage::with('printPhotoDetails.printServiceEvent.printPhoto')->whereIn('service_event_id', $serviceEventIds)->get();
        if ($packages->isEmpty()) {
            return back()->with('error', 'Belum Membuat Paket Foto pada Layanan, tambahkan sekarang!!');
        }
        $packageDetails = ServicePackageDetail::whereIn('service_package_id', $packages->pluck('id'))->get();
        $rent = Rent::with(['rentDetails.openingHour.hour'])
            ->whereIn('service_package_detail_id', $packageDetails->pluck('id'))
            ->findOrFail($id);
        $openingHourIds = $rent->rentDetails->pluck('opening_hour_id');
        if ($openingHourIds->isNotEmpty()) {
            $firstOpeningHourId = $openingHourIds->first();
            $lastOpeningHourId = $openingHourIds->last();
            $firstOpeningHour = OpeningHour::find($firstOpeningHourId)->hour;
            $lastOpeningHour = OpeningHour::find($lastOpeningHourId)->hour;
            $nextHour = Hour::where('id', $lastOpeningHour->id + 1)->first();
            if ($nextHour) {
                $rent->formatted_schedule = $firstOpeningHour->hour . ' - ' . $nextHour->hour;
            } else {
                $rent->formatted_schedule = $firstOpeningHour->hour . ' - ' . $lastOpeningHour->hour;
            }
        } else {
            $rent->formatted_schedule = null;
        }
        $bookDates = RentDetail::with('rent')
            ->get()
            ->filter(function ($rentDetail) {
                return in_array($rentDetail->rent->rent_status, [0, 1, 3, 5]);
            })
            ->map(function ($rentDetail) {
                return [
                    'rent_id' => $rentDetail->rent_id,
                    'opening_hour_id' => $rentDetail->opening_hour_id,
                    'date' => $rentDetail->rent->date,
                ];
            })
            ->values()
            ->toArray();

        $data = [
            'pageTitle' => 'Edit Booking',
            'venues' => $venues,
            'uniqueDayIds' => $uniqueDayIds,
            'openingHours' => $openingHours,
            'today' => $today,
            'services' => $services,
            'packages' => $packages,
            'packageDetails' => $packageDetails,
            'rent' => $rent,
            'bookDates' => $bookDates,
        ];
        return view('back.pages.owner.booking-manage.edit', $data);
    }
    public function update(Request $request, string $id)
    {
        try {
            $rent = Rent::findOrFail($id);
            $selectedRentId = $rent->id;
            if (!$selectedRentId) {
                return redirect()->back()->with('error', 'Selected rent ID is missing.');
            }
            if (!is_numeric($selectedRentId)) {
                return redirect()->back()->with('error', 'Selected rent ID is invalid.');
            }
            $openingHoursArray = array_filter($request->input('opening_hours'), 'is_numeric');
            $date = $request->input('date');
            Log::info("Received date: {$date}");
            if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                return redirect()->back()->withErrors(['date' => 'The date format is incorrect.'])->withInput();
            }
            Log::info("Before validation");
            $validator = Validator::make(['opening_hours' => $openingHoursArray, 'date' => $date], [
                'opening_hours' => 'required|array',
                'opening_hours.*' => 'integer|exists:opening_hours,id',
                'date' => 'required|date_format:d/m/Y',
            ], [
                'opening_hours.required' => 'Please select the opening hours.',
                'opening_hours.*.exists' => 'One or more selected opening hours are invalid.',
                'date.date_format' => 'The date format is incorrect.',
            ]);
            // dd('validasi benar');
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            Log::info("After validation");
            $dateFormatted = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
            Log::info("Deleting old RentDetails for rent_id: {$id}");
            RentDetail::where('rent_id', $id)->delete();
            Log::info("Creating new RentDetails for rent_id: {$id}");
            foreach ($openingHoursArray as $openingHourId) {
                RentDetail::create([
                    'rent_id' => $selectedRentId,
                    'opening_hour_id' => $openingHourId,
                ]);
            }
            $rent->date = $dateFormatted;
            $rent->save();
            Log::info("Updated date for rent_id: {$id} to {$date}");
            return redirect()->route('owner.booking.index')->with('success', 'Jadwal booking berhasil diperbarui.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('An error occurred while updating the booking schedule', ['exception' => $e]);
            return redirect()->route('owner.booking.index')->with('error', 'An error occurred while updating the booking schedule. Please try again.');
        }
    }
    public function store(Request $request)
    {
        Log::info('Masuk ke fungsi store');
        // dd($request->all());
        try {
            $validatedData = $request->validate([
                'name_tenant' => 'required|string|max:255',
                'venue' => 'required|integer',
                'service_type' => 'required|integer',
                'service' => 'required|integer',
                'package' => 'required|integer',
                'package_detail' => 'required|integer',
                'print_photo_detail_id' => 'nullable|integer',
                'date' => 'required|date_format:d/m/Y',
                'opening_hours' => 'required|array',
                'opening_hours.*' => 'integer',
                'total_price' => 'required|integer|min:0',
            ], [
                'name_tenant.required' => 'Mohon diisi nama dari penyewa',
                'venue.required' => 'Mohon diisi venue yang akan booking',
                'service_type.required' => 'Mohon diisi tipe layanan dari foto',
                'service.required' => 'Mohon diisi nama layanan yang akan dibooking',
                'package.required' => 'Mohon diisi nama paket yang akan dibooking',
                'package_detail.required' => 'Mohon diisi jumlah orang yang akan melakukan foto',
                'print_photo_detail_id.integer' => 'Mohon diisi ukuran cetak foto dengan benar',
                'date.required' => 'Mohon diisi tanggal booking',
                'total_price.required' => 'Mohon pilih paket foto dan cetak foto.',
            ]);

            Log::info('Validasi berhasil', ['validatedData' => $validatedData]);
            $date = Carbon::createFromFormat('d/m/Y', $validatedData['date'])->format('Y-m-d');
            $openingHours = $validatedData['opening_hours'];
            if ($this->checkDuplicateOpeningHours($openingHours)) {
                return back()->withErrors(['opening_hours' => 'Jadwal ini sudah dibooking'])->withInput();
            }
            if (Auth::guard('owner')) {
                $venueName = Venue::find($validatedData['venue'])->name;
                $faktur = $this->generateFaktur($venueName);
                $rent = new Rent();
                $rent->name = $validatedData['name_tenant'];
                $rent->faktur = $faktur;
                $rent->book_type = 0;
                $rent->rent_status = 5;
                $rent->service_package_detail_id = $validatedData['package_detail'];
                $rent->print_photo_detail_id = $validatedData['print_photo_detail_id'];
                $rent->date = $date;
                $rent->total_price = $validatedData['total_price'];
                $rent->save();

                Log::info('Data rent berhasil disimpan', ['rent' => $rent]);

                foreach ($validatedData['opening_hours'] as $index => $opening_hour) {
                    $rentDetail = new RentDetail();
                    $rentDetail->rent_id = $rent->id;
                    $rentDetail->opening_hour_id = $opening_hour;
                    $rentDetail->save();
                    Log::info('Data rent detail berhasil disimpan', ['rentDetail' => $rentDetail]);
                }
                return redirect()->route('owner.booking.show-payment', ['booking' => $rent->id])->with('success', 'Lanjutkan Ke Bagian Pembayaran Booking.');
            } else if (Auth::guard('customer')) {
                // $rent->book_type = 1;
                // $rent->rent_status = 0;
            }
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan paket foto', ['error' => $e->getMessage()]);
            return redirect()->route('owner.booking.index')->with('fail', 'Gagal menambahkan paket foto. Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function showPayment(Request $request, $booking)
    {
        $rent = Rent::findOrFail($booking);
        $venueId = $rent->servicePackageDetail->servicePackage->serviceEvent->venue->id;
        $paymentMethodDetails = PaymentMethodDetail::where('venue_id', $venueId)->with('paymentMethod')->get();
        $openingHourIds = $rent->rentDetails->pluck('opening_hour_id');
        if ($openingHourIds->isNotEmpty()) {
            $firstOpeningHourId = $openingHourIds->first();
            $lastOpeningHourId = $openingHourIds->last();
            $firstOpeningHour = OpeningHour::find($firstOpeningHourId)->hour;
            $lastOpeningHour = OpeningHour::find($lastOpeningHourId)->hour;
            $nextHour = Hour::where('id', $lastOpeningHour->id + 1)->first();
            if ($nextHour) {
                $rent->formatted_schedule = $firstOpeningHour->hour . ' - ' . $nextHour->hour;
            } else {
                $rent->formatted_schedule = $firstOpeningHour->hour . ' - ' . $lastOpeningHour->hour;
            }
        } else {
            $rent->formatted_schedule = 'Invalid time format';
        }
        return view('back.pages.owner.booking-manage.payment', compact('rent', 'paymentMethodDetails'));
    }
    public function rentPayment(Request $request, string $id)
    {
        Log::info('Masuk ke fungsi store');
        try {
            if (Auth::guard('owner')->check()) {
                $rent = Rent::findOrFail($id);
                $dpMinValue = $rent->servicePackageDetail->servicePackage->dp_percentage * $rent->total_price;
                $minPaymentValue = $rent->servicePackageDetail->servicePackage->dp_min;
                $totalPrice = $rent->total_price;
                $request->validate([
                    'dp_price' => 'required|string|in:full_payment,dp,min_payment',
                    'dp_input' => ['nullable', 'integer', 'min:0', 'required_if:dp_price,dp', function ($attribute, $value, $fail) use ($dpMinValue, $totalPrice) {
                        if ($value < $dpMinValue) {
                            $fail("DP harus lebih dari Rp " . number_format($dpMinValue, 0, ',', '.'));
                        }
                        if ($value >= $totalPrice) {
                            $fail("DP harus lebih kecil dari total Harga Rp " . number_format($totalPrice, 0, ',', '.'));
                        }
                    }],
                    'min_payment_input' => [
                        'nullable', 'integer', 'min:0', 'required_if:dp_price,min_payment',
                        function ($attribute, $value, $fail) use ($minPaymentValue, $totalPrice) {
                            if ($value < $minPaymentValue) {
                                $fail("Minimal Pembayaran harus lebih dari Rp " . number_format($minPaymentValue, 0, ',', '.'));
                            }
                            if ($value >= $totalPrice) {
                                $fail("Minimal Pembayaran harus lebih kecil dari total Harga Rp " . number_format($totalPrice, 0, ',', '.'));
                            }
                        }
                    ],
                ]);

                $paymentStatus = 0;
                $dpPrice = 0;
                if ($request->dp_price === 'full_payment') {
                    $paymentStatus = 0;
                    $dpPrice = $rent->total_price;
                } elseif ($request->dp_price === 'dp') {
                    $paymentStatus = 1;
                    $dpPrice = $request->dp_input;
                } elseif ($request->dp_price === 'min_payment') {
                    $paymentStatus = 2;
                    $dpPrice = $request->min_payment_input;
                }
                $rent->update([
                    'payment_status' => $paymentStatus,
                    'dp_price' => $dpPrice,
                    'rent_status' => 1,
                ]);
                return redirect()->route('owner.booking.index')->with('success', 'Sudah Melakukan Pembayaran');
            } else {
                return redirect()->back()->with('fail', 'Akses tidak diizinkan.');
            }
        } catch (\Exception $e) {
            Log::error('Gagal melakukan pembayaran', ['error' => $e->getMessage()]);
            return redirect()->back()->with('fail', 'Gagal Melakukan Pembayaran : ' . $e->getMessage());
        }
    }
    private function generateFaktur($venueName)
    {
        $initials = strtoupper(substr($venueName, 0, 5));
        $timestamp = date('HisdmY');
        $randomNumber = rand(100, 999);
        return "{$initials}{$timestamp}{$randomNumber}";
    }
    public function destroy(string $id)
    {
    }
    // public function create()
    // {

    //     return view('back.pages.owner.booking-manage.create');
    // }

    public function getVenues($ownerId)
    {
        $venues = Venue::where('owner_id', $ownerId)->where('status', 1)->get();
        return response()->json($venues);
    }
    public function getServices($venueId)
    {
        $services = ServiceEvent::with('serviceType')->where('venue_id', $venueId)->get();
        Log::info($services);
        return response()->json($services);
    }
    public function getServicesByTypeAndVenue($venueId, $serviceTypeId)
    {
        $services = ServiceEvent::with('serviceType')
            ->where('venue_id', $venueId)
            ->where('service_type_id', $serviceTypeId)
            ->get();
        return response()->json($services);
    }
    public function getPackages($serviceEventId)
    {
        $packages = ServicePackage::with(['addOnPackageDetails.addOnPackage'])
            ->where('service_event_id', $serviceEventId)
            ->get();
        $packages->each(function ($package) {
            if ($package->addOnPackageDetails) {
                $package->load('addOnPackageDetails.addOnPackage');
            }
        });

        return response()->json($packages);
    }
    public function getPackageDetails($packageId)
    {
        $packageDetails = ServicePackageDetail::where('service_package_id', $packageId)->get();
        return response()->json($packageDetails);
    }
    public function getPrintPhotoDetails($packageId)
    {
        $printPhotoDetails = PrintPhotoDetail::with('printServiceEvent.printPhoto')->where('service_package_id', $packageId)->get();
        return response()->json($printPhotoDetails);
    }
    public function getBookDates(Request $request)
    {
        $venueId = $request->input('venue_id');
        $selectedDate = $request->input('selected_date');
        $venue = Venue::find($venueId);
        if (!$venue) {
            return response()->json(['error' => 'Venue not found'], 404);
        }
        $openingHours = OpeningHour::where('venue_id', $venueId)->pluck('id');
        $bookDates = RentDetail::whereIn('opening_hour_id', $openingHours)
            ->whereHas('rent', function ($query) use ($selectedDate) {
                $query->where('date', $selectedDate);
            })
            ->get()
            ->map(function ($rentDetail) {
                return [
                    'opening_hour_id' => $rentDetail->opening_hour_id,
                    'date' => $rentDetail->rent->date,
                ];
            })
            ->toArray();
        return response()->json($bookDates);
    }
    private function checkDuplicateOpeningHours(array $openingHours): bool
    {
        $query = DB::table('rent_details')
            ->select(DB::raw('COUNT(*) as total'))
            ->whereIn('opening_hour_id', $openingHours)
            ->groupBy('opening_hour_id')
            ->havingRaw('total > 1');
        return $query->exists();
    }
}
