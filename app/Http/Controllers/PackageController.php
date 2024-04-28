<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceEvent;
use App\Models\ServiceEventImage;
use App\Models\ServiceType;
use App\Models\ServicePackage;
use App\Models\AddOnPackage;
use App\Models\PrintPhoto;
use App\Models\PrintServiceEvent;
use App\Models\PrintPhotoDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Venue;
use App\Models\Admin;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodDetail;
use App\Models\OpeningHour;
use App\Models\VenueImage;
use App\Models\Owner;
use App\Models\Day;
use App\Models\Hour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\TryCatch;

class PackageController extends Controller
{

    public function index($venueId, $serviceId)
    {
    }


    public function create($venueId, $serviceId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $addOnPackages = AddOnPackage::all();
            $printServiceEvents = PrintServiceEvent::where('service_event_id', $serviceId)->get();
            return view('back.pages.owner.package-manage.create', compact('venue', 'service', 'addOnPackages', 'printServiceEvents'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }


    public function store(Request $request, $venueId, $serviceId)
    {
    }


    public function show($venueId, $serviceId, $packageId)
    {
        //
    }


    public function edit($venueId, $serviceId, $packageId)
    {
        //
    }


    public function update(Request $request, $venueId, $serviceId, $packageId)
    {
        //
    }

    public function destroy($venueId, $serviceId, $packageId)
    {
        //
    }
}
