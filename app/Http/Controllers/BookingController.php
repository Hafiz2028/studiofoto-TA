<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\OpeningHour;
use App\Models\ServiceEvent;
use Illuminate\Http\Request;
use App\models\ServicePackage;
use App\Models\ServicePackageDetail;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ownerId = Auth::guard('owner')->id();
        $venues = Venue::where('owner_id', $ownerId)->where('status', 1)->get();
        if ($venues->isEmpty()) {
            return view('back.pages.owner.venue-manage.index-venue')->with('error', 'Tidak ada venue yang terdaftar, daftarkan sekarang');
        }
        $venueIds = $venues->pluck('id')->toArray();
        $openingHours = OpeningHour::whereIn('venue_id', $venueIds)->get();
        $uniqueDayIds = collect($openingHours)->unique('day_id')->pluck('day_id')->toArray();
        $today = now()->format('Y-m-d');
        $services = ServiceEvent::with('serviceType')->whereIn('venue_id', $venueIds)->get();
        if ($services->isEmpty()) {
            return view('back.pages.owner.venue-manage.index-venue')->with('error', 'Belum ada layanan yang ditambahkan, tambahkan sekarang');
        }
        $serviceEventIds = $services->pluck('id')->toArray();
        $packages = ServicePackage::with('printPhotoDetails.printServiceEvent.printPhoto')->whereIn('service_event_id', $serviceEventIds)->get();
        if ($packages->isEmpty()) {
            return view('back.pages.owner.venue-manage.index-venue')->with('error', 'Anda belum membuat paket foto, tambahkan sekarang');
        }
        $packageDetails = ServicePackageDetail::whereIn('service_package_id', $packages->pluck('id'))->get();
        // dd($openingHours->toArray());
        $data = [
            'pageTitle' => 'Booking',
            'venues' => $venues,
            'uniqueDayIds' => $uniqueDayIds,
            'openingHours' => $openingHours,
            'today' => $today,
            'services' => $services,
            'packages' => $packages,
            'packageDetails' => $packageDetails,
        ];
        return view('back.pages.owner.booking-manage.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('back.pages.owner.booking-manage.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
