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

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $ownerId = Auth::guard('owner')->id();
        $venueIds = Venue::where('owner_id', $ownerId)->pluck('id');
        $venues = Venue::where('owner_id', $ownerId)
            ->where('status', 1)
            ->get();

        $selectedVenues = $request->input('venue_selects', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $rentsQuery = Rent::whereIn('rent_status', [2, 3, 4, 7])
            ->whereHas('servicePackageDetail.servicePackage.serviceEvent', function ($query) use ($venueIds, $selectedVenues) {
                if (!empty($selectedVenues)) {
                    $query->whereIn('venue_id', $selectedVenues);
                } else {
                    $query->whereIn('venue_id', $venueIds);
                }
            });

        if ($startDate) {
            $rentsQuery->whereDate('date', '>=', $startDate);
        }

        if ($endDate) {
            $rentsQuery->whereDate('date', '<=', $endDate);
        }
        $rents = $rentsQuery->get();
        $totalIncome = 0;
        foreach ($rents as $rent) {
            if ($rent->dp_payment == null) {
                if ($rent->dp_price == 0) {
                    $totalIncome += 0;
                } else {
                    $totalIncome += $rent->dp_price;
                }
            } else {
                $totalIncome += $rent->dp_price;
            }
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
            'totalIncome' => $totalIncome,
            'venues' => $venues,
            'selectedVenues' => $selectedVenues,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        return view('back.pages.owner.transaction-manage.index', $data);
    }
}
