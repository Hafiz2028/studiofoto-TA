<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceEvent;
use App\Models\ServiceEventImage;
use App\Models\ServiceType;
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

class ServiceController extends Controller
{
    public function index()
    {
        $services = ServiceEvent::all();
        return view('services.index', ['services' => $services]);
    }

    public function create($venueId)
    {

        try {
            $venue = Venue::findOrFail($venueId);
            return view('back.pages.owner.service-manage.create', compact('venue'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        //
    }
    public function show($venueId, $serviceId)
    {
        //
    }
    public function edit($venueId, $serviceId)
    {
        //
    }
    public function update(Request $request, $venueId, $serviceId)
    {
        //
    }
    public function destroy($venueId, $serviceId)
    {
        //
    }
}
