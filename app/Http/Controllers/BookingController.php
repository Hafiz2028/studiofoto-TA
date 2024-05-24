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
use Illuminate\Support\Facades\Log;
use App\Models\ServicePackageDetail;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
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
                $rent->formatted_schedule = 'Invalid time format';
            }
        }
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
        ];
        return view('back.pages.owner.booking-manage.index', $data);
    }
    public function store(Request $request)
    {
        Log::info('Masuk ke fungsi store');
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
            if (Auth::guard('owner')) {
                $venueName = Venue::find($validatedData['venue'])->name;
                $faktur = $this->generateFaktur($venueName);
                $rent = new Rent();
                $rent->name = $validatedData['name_tenant'];
                $rent->faktur = $faktur;
                $rent->book_type = 0;
                $rent->rent_status = 1;
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
        return view('back.pages.owner.booking-manage.payment', compact('rent'));
    }
    public function rentPayment(Request $request, string $id)
    {
    }
    public function show(string $id)
    {
    }
    private function generateFaktur($venueName)
    {
        $initials = strtoupper(substr($venueName, 0, 3));
        $timestamp = date('HisdmY');
        $randomNumber = rand(100, 999);
        return "{$initials}{$timestamp}{$randomNumber}";
    }




    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
    }

    public function destroy(string $id)
    {
    }
    // public function create()
    // {

    //     return view('back.pages.owner.booking-manage.create');
    // }
}
