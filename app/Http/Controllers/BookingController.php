<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Hour;
use App\Models\Rent;
use App\Models\Venue;
use App\Models\RentDetail;
use App\Models\OpeningHour;
use App\Models\RentPayment;
use App\Models\ServiceType;
use App\Models\RentCustomer;
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
use Illuminate\Support\Facades\File;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            $rentIds = RentCustomer::where('customer_id', $customer->id)->pluck('rent_id');
            $rents = Rent::with(['rentDetails.openingHour.hour'])
                ->whereIn('id', $rentIds)
                ->whereIn('rent_status', [0, 1, 5, 6])
                ->get();
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
            $data = [
                'rents' => $rents,
            ];
            return view('front.pages.booking-manage.index', $data);
        } elseif (Auth::guard('owner')->check()) {
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
            $packages = ServicePackage::with('printPhotoDetails.printPhoto', 'servicePackageDetails', 'framePhotoDetails.printPhoto', 'addOnPackageDetails.addOnPackage')
                ->whereHas('serviceEvent', function ($query) use ($venueIds) {
                    $query->whereIn('venue_id', $venueIds);
                })
                ->get();
            if ($packages->isEmpty()) {
                return back()->with('error', 'Belum Membuat Paket Foto pada Layanan, tambahkan sekarang!!');
            }
            $packageDetails = ServicePackageDetail::whereIn('service_package_id', $packages->pluck('id'))->get();
            $rents = Rent::with(['rentDetails.openingHour.hour'])->whereIn('service_package_detail_id', $packageDetails->pluck('id'))->whereIn('rent_status', [0, 1, 5, 6])->get();
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
    }

    public function approveRent(Request $request, String $id)
    {
        $rent = Rent::findOrFail($id);
        $rent->rent_status = 1;
        $saved = $rent->save();
        if ($saved) {
            return redirect()->route('owner.booking.index')->with('success', 'Jadwal Booking <b>' . ucfirst($rent->name) . '</b> dengan no Faktur <b>' . ucfirst($rent->faktur) . '</b> telah di Approve');
        } else {
            return redirect()->route('owner.booking.index')->with('fail', 'Jadwal Booking gagal di Approve, coba lagi');
        }
    }
    public function rejectRent(Request $request, String $id)
    {
        $rent = Rent::findOrFail($id);
        $rent->rent_status = 3;
        $rent->reject_note = $request->input('reject_note');
        $saved = $rent->save();
        if ($saved) {
            return redirect()->route('owner.booking.index')->with('success', 'Jadwal Booking <b>' . ucfirst($rent->name) . '</b> dengan no Faktur <b>' . ucfirst($rent->faktur) . '</b> telah di Reject');
        } else {
            return redirect()->route('owner.booking.index')->with('fail', 'Jadwal Booking gagal di Reject, coba lagi');
        }
    }
    public function batalRent(Request $request, String $id)
    {
        $rent = Rent::findOrFail($id);
        $rent->rent_status = 7;
        $rent->reject_note = $request->input('reject_note');
        $saved = $rent->save();
        if ($saved) {
            return redirect()->route('owner.booking.index')->with('success', 'Jadwal Booking <b>' . ucfirst($rent->name) . '</b> dengan no Faktur <b>' . ucfirst($rent->faktur) . '</b> telah di Batalkan');
        } else {
            return redirect()->route('owner.booking.index')->with('fail', 'Jadwal Booking gagal di Batalkan, coba lagi');
        }
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
    public function updateStatusRentCust(Request $request, String $id)
    {
        $rent = Rent::findOrFail($id);
        $rent->rent_status = $request->input('status');
        $rent->save();

        return response()->json(['message' => 'Rent status updated successfully']);
    }
    public function updateStatusMulaiFoto(Request $request, String $id)
    {
        $cutoffTime = Carbon::now()->setSeconds(0);
        $rent = Rent::findOrFail($id);
        $expiredRent = null;
        $openingHour = null;
        $scheduleTime = null;
        if ($rent->rent_status == 6) {
            $rent->rent_status = 2;
            $rent->save();
            return response()->json([
                'success' => true,
                'message' => 'Pemotretan Telah Selesai dilakukan'
            ]);
        }
        if ($rent->rent_status == 1 || $rent->rent_status == 5 || $rent->rent_status == 0) {
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
                $expiredRent = ['faktur' => $rent->faktur, 'name' => $rent->name];
            }
        }
        if ($expiredRent) {
            return response()->json([
                'status' => 'expired',
                'message' => ['Jadwal ini sudah expired.', "Faktur: {$expiredRent['faktur']}, Nama Cust: {$expiredRent['name']}"]
            ]);
        } else {
            $rent->rent_status = 6;
            $rent->save();
            return response()->json([
                'status' => 'success',
                'message' => ['Jadwal tidak expired, status berhasil diupdate.'],
                'openingHour' => $openingHour ? $openingHour->toArray() : null,
                'cutoffTime' => $cutoffTime->toArray()
            ]);
        }
    }
    public function show(string $id)
    {
        $rent = Rent::findOrFail($id);
        $openingHourIds = $rent->rentDetails->pluck('opening_hour_id');
        $firstOpeningHour = null;
        $lastOpeningHour = null;
        $formattedLastOpeningHour = null;
        if ($openingHourIds->isNotEmpty()) {
            $firstOpeningHourId = $openingHourIds->first();
            $lastOpeningHourId = $openingHourIds->last();
            $firstOpeningHour = OpeningHour::find($firstOpeningHourId)->hour;
            $lastOpeningHour = OpeningHour::find($lastOpeningHourId)->hour;
            $lastOpeningHourFormatted = Carbon::createFromFormat('H.i', $lastOpeningHour->hour)->format('H:i');
            Log::info('Nilai lastOpeningHour sebelum penambahan 30 menit: ' . $lastOpeningHourFormatted);
            $formattedLastOpeningHour = Carbon::parse($lastOpeningHourFormatted)->addMinutes(30)->format('H:i');
            Log::info('Nilai formattedLastOpeningHour setelah penambahan 30 menit: ' . $formattedLastOpeningHour);
            $rent->formatted_schedule = $firstOpeningHour->hour . ' - ' . $formattedLastOpeningHour;
        } else {
            $rent->formatted_schedule = null;
        }

        $data = [
            'rent' => $rent,
            'firstOpeningHour' => $firstOpeningHour,
            'lastOpeningHour' => $lastOpeningHour,
            'formattedLastOpeningHour' => $formattedLastOpeningHour,

        ];
        if (Auth::guard('customer')->check()) {
            return view('front.pages.booking-manage.show', $data);
        } elseif (Auth::guard('owner')->check()) {
            return view('back.pages.owner.booking-manage.show', $data);
        }
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
        $packages = ServicePackage::with('printPhotoDetails.printPhoto')->whereIn('service_event_id', $serviceEventIds)->get();
        if ($packages->isEmpty()) {
            return back()->with('error', 'Belum Membuat Paket Foto pada Layanan, tambahkan sekarang!!');
        }
        $packageDetails = ServicePackageDetail::whereIn('service_package_id', $packages->pluck('id'))->get();
        $rent = Rent::with(['rentDetails.openingHour.hour'])
            ->whereIn('service_package_detail_id', $packageDetails->pluck('id'))
            ->findOrFail($id);
        $openingHourIds = $rent->rentDetails->pluck('opening_hour_id');
        $firstOpeningHour = null;
        $lastOpeningHour = null;
        $formattedLastOpeningHour = null;
        if ($openingHourIds->isNotEmpty()) {
            $firstOpeningHourId = $openingHourIds->first();
            $lastOpeningHourId = $openingHourIds->last();
            $firstOpeningHour = OpeningHour::find($firstOpeningHourId)->hour;
            $lastOpeningHour = OpeningHour::find($lastOpeningHourId)->hour;
            $lastOpeningHourFormatted = Carbon::createFromFormat('H.i', $lastOpeningHour->hour)->format('H:i');
            Log::info('Nilai lastOpeningHour sebelum penambahan 30 menit: ' . $lastOpeningHourFormatted);
            $formattedLastOpeningHour = Carbon::parse($lastOpeningHourFormatted)->addMinutes(30)->format('H:i');
            Log::info('Nilai formattedLastOpeningHour setelah penambahan 30 menit: ' . $formattedLastOpeningHour);
            $rent->formatted_schedule = $firstOpeningHour->hour . ' - ' . $formattedLastOpeningHour;
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
            'firstOpeningHour' => $firstOpeningHour,
            'lastOpeningHour' => $lastOpeningHour,
            'formattedLastOpeningHour' => $formattedLastOpeningHour,
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
        try {
            $validatedData = $request->validate([
                'name_tenant' => 'required|string|max:255',
                'no_hp' => 'required|string|min:5|max:13',
                'venue' => 'required|integer',
                'service' => 'required|integer',
                'package_detail' => 'required|integer',
                'date' => 'required|date_format:d/m/Y',
                'opening_hours' => 'required|array',
                'opening_hours.*' => 'integer',
                'total_price' => 'required|integer|min:0',
            ], [
                'name_tenant.required' => 'Mohon diisi nama dari penyewa',
                'no_hp.required' => 'Mohon diisi nomor handphone penyewa yang bisa dihubungi',
                'no_hp.min' => 'Minimal nomor hp adalah 11 angka',
                'no_hp.max' => 'Maksimal nomor hp adalah 13 angka',
                'venue.required' => 'Mohon diisi venue yang akan booking',
                'service.required' => 'Mohon diisi nama layanan yang akan dibooking',
                'package_detail.required' => 'Mohon diisi jumlah orang yang akan melakukan foto',
                'date.required' => 'Mohon diisi tanggal booking',
                'opening_hours.required' => 'Jangan Kosongkan Jadwal yang akan dibooking.',
                'total_price.required' => 'Mohon pilih paket foto dan cetak foto.',
            ]);
            Log::info('Validasi berhasil', ['validatedData' => $validatedData]);
            $date = Carbon::createFromFormat('d/m/Y', $validatedData['date'])->format('Y-m-d');
            Log::info('Tanggal berhasil diformat', ['date' => $date]);
            $openingHours = $validatedData['opening_hours'];
            Log::info('Opening hours diterima', ['openingHours' => $openingHours]);
            // if ($this->checkDuplicateOpeningHours($openingHours)) {
            //     Log::info('Jadwal sudah dibooking', ['openingHours' => $openingHours]);
            //     return back()->withErrors(['opening_hours' => 'Jadwal ini sudah dibooking'])->withInput();
            // }
            $venueName = Venue::find($validatedData['venue'])->name;
            Log::info('Venue ditemukan', ['venueName' => $venueName]);
            $faktur = $this->generateFaktur($venueName);
            Log::info('Faktur berhasil dibuat', ['faktur' => $faktur]);

            $rent = new Rent();
            $rent->name = $validatedData['name_tenant'];
            $rent->no_hp = $validatedData['no_hp'];
            $rent->faktur = $faktur;
            $rent->service_package_detail_id = $validatedData['package_detail'];
            $rent->date = $date;
            $rent->total_price = $validatedData['total_price'];
            $rent->rent_status = 5;
            if (Auth::guard('owner')->check()) {
                $rent->book_type = 0;
                $rent->save();
                Log::info('Data rent berhasil disimpan', ['rent' => $rent]);
                foreach ($openingHours as $index => $opening_hour) {
                    $rentDetail = new RentDetail();
                    $rentDetail->rent_id = $rent->id;
                    $rentDetail->opening_hour_id = $opening_hour;
                    $rentDetail->save();
                    Log::info('Data rent detail berhasil disimpan', ['rentDetail' => $rentDetail]);
                }
                return redirect()->route('owner.booking.show-payment', ['booking' => $rent->id])->with('success', 'Lanjutkan Ke Bagian Pembayaran Booking.');
            } else if (Auth::guard('customer')->check()) {
                $rent->book_type = 1;
                $rent->save();
                Log::info('Data rent berhasil disimpan oleh customer', ['rent' => $rent]);
                foreach ($openingHours as $index => $opening_hour) {
                    $rentDetail = new RentDetail();
                    $rentDetail->rent_id = $rent->id;
                    $rentDetail->opening_hour_id = $opening_hour;
                    $rentDetail->save();
                    Log::info('Data rent detail berhasil disimpan', ['rentDetail' => $rentDetail]);
                }
                $customer_id = Auth::guard('customer')->id();
                $rentCustomer = new RentCustomer();
                $rentCustomer->rent_id = $rent->id;
                $rentCustomer->customer_id = $customer_id;
                $rentCustomer->save();
                Log::info('Data rent customer berhasil disimpan', ['rentCustomer' => $rentCustomer]);
                return redirect()->route('customer.booking.show-payment', ['booking' => $rent->id])->with('success', 'Jadwal Berhasil dibooking, lanjutkan ke pembayaran.');
            }
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan paket foto', ['error' => $e->getMessage()]);
            if (Auth::guard('customer')->check()) {
                return redirect()->back()->with('fail', 'Gagal menambahkan paket foto. Terjadi kesalahan: ' . $e->getMessage());
            } elseif (Auth::guard('owner')->check()) {
                return redirect()->route('owner.booking.index')->with('fail', 'Gagal menambahkan paket foto. Terjadi kesalahan: ' . $e->getMessage());
            }
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


        if (Auth::guard('customer')->check()) {
            $data = [
                'pageTitle' => 'FotoYuk | Payment',
                'rent' => $rent,
                'venueId' => $venueId,
                'paymentMethodDetails' => $paymentMethodDetails,
            ];
            return view('front.pages.payment-manage.show-payment', $data);
        } elseif (Auth::guard('owner')->check()) {
            $data = [
                'pageTitle' => 'FotoYuk | Payment',
                'rent' => $rent,
                'venueId' => $venueId,
                'paymentMethodDetails' => $paymentMethodDetails
            ];
            return view('back.pages.owner.booking-manage.payment', $data);
        }
    }
    public function rentPayment(Request $request, string $id)
    {
        Log::info('Masuk ke fungsi store');
        try {
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
            $dpPaymentDate = null;
            if ($request->dp_price === 'full_payment') {
                $paymentStatus = 0;
                $dpPrice = $rent->total_price;
                $dpPaymentDate = Carbon::now();
            } elseif ($request->dp_price === 'dp') {
                $paymentStatus = 1;
                $dpPrice = $request->dp_input;
            } elseif ($request->dp_price === 'min_payment') {
                $paymentStatus = 2;
                $dpPrice = $request->min_payment_input;
            }
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $rent->update([
                'payment_status' => $paymentStatus,
                'dp_price' => $dpPrice,
                'rent_status' => 1,
                'dp_price_date' => $currentDateTime,
                'dp_payment' => $dpPaymentDate ? $dpPaymentDate->format('Y-m-d H:i:s') : null,
            ]);
            return redirect()->route('owner.booking.index')->with('success', 'Sudah Melakukan Pembayaran');
        } catch (\Exception $e) {
            Log::error('Gagal melakukan pembayaran', ['error' => $e->getMessage()]);
            return redirect()->back()->with('fail', 'Gagal Melakukan Pembayaran : ' . $e->getMessage());
        }
    }
    public function rentPaymentCust(Request $request, string $id)
    {
        Log::info('Masuk ke fungsi store cust');
        try {
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
                'paymentMethod' => 'required|exists:payment_method_details,id',
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $paymentStatus = 0;
            $dpPrice = 0;
            $dpPaymentDate = null;
            if ($request->dp_price === 'full_payment') {
                $paymentStatus = 0;
                $dpPrice = $rent->total_price;
                $dpPaymentDate = Carbon::now();
            } elseif ($request->dp_price === 'dp') {
                $paymentStatus = 1;
                $dpPrice = $request->dp_input;
            } elseif ($request->dp_price === 'min_payment') {
                $paymentStatus = 2;
                $dpPrice = $request->min_payment_input;
            }
            $currentDateTime = Carbon::now()->format('Y-m-d H:i:s');
            $rent->update([
                'payment_status' => $paymentStatus,
                'dp_price' => $dpPrice,
                'rent_status' => 0,
                'dp_price_date' => $currentDateTime,
                'dp_payment' => $dpPaymentDate ? $dpPaymentDate->format('Y-m-d H:i:s') : null,
            ]);
            $file = $request->file('bukti_pembayaran');
            $extension = $file->getClientOriginalExtension();
            $buktiName = 'BuktiPembayaran_' . $rent->faktur . '_' . uniqid() . '.' . $extension;
            $buktiPath = $request->file('bukti_pembayaran')->storeAs('/Bukti_Pembayaran', $buktiName, 'public');
            RentPayment::create([
                'image' => $buktiName,
                'payment_type' => $paymentStatus === 0 ? 'Lunas' : 'DP',
                'rent_id' => $rent->id,
                'payment_method_detail_id' => $request->paymentMethod,
            ]);
            return redirect()->route('customer.booking.index')->with('success', 'Sudah Melakukan Pembayaran');
        } catch (\Exception $e) {
            Log::error('Gagal melakukan pembayaran', ['error' => $e->getMessage()]);
            dd($request->all);
            return redirect()->back()->with('fail', 'Gagal Melakukan Pembayaran : ' . $e->getMessage());
        }
    }
    public function showPaymentLunas(Request $request, $booking)
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
        return view('back.pages.owner.booking-manage.payment-lunas', compact('rent', 'paymentMethodDetails'));
    }

    public function rentPaymentLunas(Request $request, string $id)
    {
        Log::info('Masuk ke fungsi store');
        try {
            if (Auth::guard('owner')->check()) {
                $rent = Rent::findOrFail($id);
                $rent->update([
                    'dp_payment' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
                if ($rent->rent_status == 6) {
                    $rent->update([
                        'rent_status' => 2,
                    ]);
                }

                return redirect()->route('owner.booking.show', $id)->with('success', 'Sudah Melakukan Pelunasan');
            } else {
                return redirect()->back()->with('fail', 'Akses tidak diizinkan.');
            }
        } catch (\Exception $e) {
            Log::error('Gagal melakukan Pelunasan', ['error' => $e->getMessage()]);
            return redirect()->back()->with('fail', 'Gagal Melakukan Pelunasan : ' . $e->getMessage());
        }
    }
    private function generateFaktur($venueName)
    {
        $cleanedName = preg_replace('/[^a-zA-Z0-9]/', '', $venueName);
        $initials = strtoupper(substr($cleanedName, 0, 5));
        $timestamp = date('HisdmY');
        $randomNumber = rand(100, 999);
        return "{$initials}{$timestamp}{$randomNumber}";
    }
    public function destroy(string $id)
    {
        try {
            $rent = Rent::findOrFail($id);
            // $rentName = $rent->name;
            $rent->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
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
    public function getServicesAndEvents($venueId)
    {
        $serviceTypes = ServiceType::whereHas('serviceEvents', function ($query) use ($venueId) {
            $query->where('venue_id', $venueId);
        })->with(['serviceEvents' => function ($query) use ($venueId) {
            $query->where('venue_id', $venueId);
        }])->get();

        return response()->json($serviceTypes);
    }

    public function getPackageAndDetails($serviceEventId)
    {
        $packages = ServicePackage::with([
            'servicePackageDetails',
            'printPhotoDetails.printPhoto',
            'framePhotoDetails.printPhoto',
            'addOnPackageDetails.addOnPackage'
        ])
            ->where('service_event_id', $serviceEventId)
            ->get();

        return response()->json($packages);
    }
    public function getPackageDetails($packageId)
    {
        $packageDetails = ServicePackageDetail::where('service_package_id', $packageId)->get();
        return response()->json($packageDetails);
    }
    public function getPrintPhotoDetails($packageId)
    {
        $printPhotoDetails = PrintPhotoDetail::with('printPhoto')->where('service_package_id', $packageId)->get();
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
