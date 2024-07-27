<?php

namespace App\Http\Controllers;

use App\Models\Hour;
use App\Models\Rent;
use App\Models\Venue;
use App\Models\OpeningHour;
use App\Models\RentCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::guard('owner')->check()) {
            $ownerId = Auth::guard('owner')->id();
            $venueIds = Venue::where('owner_id', $ownerId)->pluck('id');
            $rents = Rent::whereIn('rent_status', [2, 3, 4, 7])
                ->whereHas('servicePackageDetail.servicePackage.serviceEvent', function ($query) use ($venueIds) {
                    $query->whereIn('venue_id', $venueIds);
                })
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
            return view('back.pages.owner.history-manage.index', compact('rents'));
        } elseif (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();
            $rentIds = RentCustomer::where('customer_id', $customerId)->pluck('rent_id');
            $venueIds = Rent::whereIn('id', $rentIds)
                ->with(['servicePackageDetail.servicePackage.serviceEvent' => function ($query) {
                    $query->select('id', 'venue_id');
                }])
                ->get()
                ->pluck('servicePackageDetail.servicePackage.serviceEvent.venue_id')
                ->unique()
                ->toArray();
            $rents = Rent::whereIn('rent_status', [2, 3, 4, 7])
                ->whereIn('id', $rentIds)
                ->whereHas('servicePackageDetail.servicePackage.serviceEvent', function ($query) use ($venueIds) {
                    $query->whereIn('venue_id', $venueIds);
                })
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
            return view('front.pages.history-manage.index', $data);
        }
    }
}
