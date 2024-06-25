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

class HistoryController extends Controller
{
    public function index(Request $request)
    {
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
    }
}
