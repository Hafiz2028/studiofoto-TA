<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Hour;
use App\Models\Rent;
use App\Models\Venue;
use App\Models\Village;
use App\Models\District;
use App\Models\RentDetail;
use App\Models\OpeningHour;
use App\Models\ServiceType;
use App\Models\AddOnPackage;
use App\Models\ServiceEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ServicePackage;
use App\Models\PrintPhotoDetail;
use App\Models\AddOnPackageDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ServicePackageDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class FrontEndController extends Controller
{
    private $venueId;
    public function home(Request $request)
    {

        $data = [
            'pageTitle' => 'FotoYuk | Home Page'
        ];

        if ($request->user('customer')) {
            $data['customer'] = $request->user('customer');
        }

        return view('front.pages.home', $data);
    }
    public function searchPage(Request $request)
    {
        $sort = $request->query('sort', 'name_asc');
        $villageId = $request->query('village_id');
        $districtId = $request->query('district_id');
        list($sortBy, $sortDirection) = explode('_', $sort);

        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        $villages = Village::with('district')->orderBy('name', 'ASC')->get();
        $districts = $villages->groupBy('district.name');
        $venues = collect(get_venues_with_service_slug());
        if ($villageId) {
            $venues = $venues->where('village_id', $villageId);
        } elseif ($districtId) {
            $villageIds = Village::where('district_id', $districtId)->pluck('id');
            $venues = $venues->whereIn('village_id', $villageIds);
        }
        if ($sortBy === 'price') {
            $venues = $venues->sortBy('min_price', SORT_REGULAR, $sortDirection === 'desc');
        } else {
            $venues = $venues->sortBy($sortBy, SORT_REGULAR, $sortDirection === 'desc');
        }

        // Pagination
        $perPage = 12;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $venues->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new LengthAwarePaginator($currentItems, $venues->count(), $perPage);
        $paginatedItems->setPath($request->url());

        $data = [
            'pageTitle' => 'FotoYuk | Search Venue Page',
            'venues' => $paginatedItems,
            'sort' => $sort,
            'villages' => $villages,
            'districts' => $districts,
        ];

        if ($request->user('customer')) {
            $data['customer'] = $request->user('customer');
        }

        return view('front.pages.search', $data);
    }
    public function detailVenue(Request $request, $id)
    {
        $customer = Auth::guard('customer')->user();
        $this->venueId = $id;
        $venue = Venue::findOrFail($id);
        $minPrice = null;
        $maxPrice = 0;
        $hasPackage = false;
        foreach ($venue->serviceEvents as $serviceEvent) {
            foreach ($serviceEvent->servicePackages as $package) {
                $hasPackage = true;

                foreach ($package->servicePackageDetails as $packageDetail) {
                    $minPrice = ($minPrice == 0) ? $packageDetail->price : min($minPrice, $packageDetail->price);
                    $maxPrice = max($maxPrice, $packageDetail->price);
                }
            }
        }
        if (!$hasPackage) {
            $minPrice = 0;
            $maxPrice = 0;
        }
        if ($customer) {
            $serviceTypes = ServiceType::whereHas('serviceEvents', function ($query) use ($id) {
                $query->where('venue_id', $id);
            })->get();
            $openingHours = OpeningHour::with('day', 'hour')->where('venue_id', $id)->get();
            $uniqueDayIds = collect($openingHours)->unique('day_id')->pluck('day_id')->toArray();
            $today = now()->format('Y-m-d');
            $services = ServiceEvent::with('serviceType')->where('venue_id', $id)->get();
            $serviceEventIds = $services->pluck('id')->toArray();
            $packages = ServicePackage::with('printPhotoDetails.printPhoto', 'addOnPackageDetails.addOnPackage', 'framePhotoDetails.printPhoto')->whereIn('service_event_id', $serviceEventIds)->get();
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
                ->whereHas('rent.servicePackageDetail.servicePackage.serviceEvent', function ($query) use ($id) {
                    $query->where('venue_id', $id);
                })
                ->whereHas('rent.rentDetails.openingHour', function ($query) use ($id) {
                    $query->where('venue_id', $id);
                })
                ->whereHas('rent', function ($query) {
                    $query->whereIn('rent_status', [0, 1, 5, 6]);
                })
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
                'pageTitle' => 'FotoYuk | Detail Venue Page',
                'customer' => $customer,
                'venue' => $venue,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
                'uniqueDayIds' => $uniqueDayIds,
                'openingHours' => $openingHours,
                'today' => $today,
                'services' => $services,
                'packages' => $packages,
                'packageDetails' => $packageDetails,
                'rents' => $rents,
                'bookDates' => $bookDates,
                'serviceTypes' => $serviceTypes,
            ];
        }
        return view('front.pages.detail', $data);
    }

    public function detailVenueNotLogin(Request $request, $id)
    {
        $this->venueId = $id;
        $venue = Venue::findOrFail($id);
        $minPrice = null;
        $maxPrice = 0;
        $hasPackage = false;
        foreach ($venue->serviceEvents as $serviceEvent) {
            foreach ($serviceEvent->servicePackages as $package) {
                $hasPackage = true;

                foreach ($package->servicePackageDetails as $packageDetail) {
                    $minPrice = ($minPrice == 0) ? $packageDetail->price : min($minPrice, $packageDetail->price);
                    $maxPrice = max($maxPrice, $packageDetail->price);
                }
            }
        }
        if (!$hasPackage) {
            $minPrice = 0;
            $maxPrice = 0;
        }
        $serviceTypes = ServiceType::whereHas('serviceEvents', function ($query) use ($id) {
            $query->where('venue_id', $id);
        })->get();
        $openingHours = OpeningHour::with('day', 'hour')->where('venue_id', $id)->get();
        $uniqueDayIds = collect($openingHours)->unique('day_id')->pluck('day_id')->toArray();
        $today = now()->format('Y-m-d');
        $services = ServiceEvent::with('serviceType')->where('venue_id', $id)->get();
        $serviceEventIds = $services->pluck('id')->toArray();
        $packages = ServicePackage::with('printPhotoDetails.printPhoto', 'addOnPackageDetails.addOnPackage', 'framePhotoDetails.printPhoto')->whereIn('service_event_id', $serviceEventIds)->get();
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
            ->whereHas('rent.servicePackageDetail.servicePackage.serviceEvent', function ($query) use ($id) {
                $query->where('venue_id', $id);
            })
            ->whereHas('rent.rentDetails.openingHour', function ($query) use ($id) {
                $query->where('venue_id', $id);
            })
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
            'pageTitle' => 'FotoYuk | Detail Venue Page',
            'venue' => $venue,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'uniqueDayIds' => $uniqueDayIds,
            'openingHours' => $openingHours,
            'today' => $today,
            'services' => $services,
            'packages' => $packages,
            'packageDetails' => $packageDetails,
            'rents' => $rents,
            'bookDates' => $bookDates,
            'serviceTypes' => $serviceTypes,
        ];
        return view('front.pages.detail', $data);
    }

    public function getServicesByTypeAndVenue(Request $request, $serviceTypeId)
    {
        $venueId = $request->query('venue_id');
        Log::info('venue_id: ' . $venueId);
        Log::info('service_type_id: ' . $serviceTypeId);
        $services = ServiceEvent::with('serviceType')
            ->where('venue_id', $venueId)
            ->where('service_type_id', $serviceTypeId)
            ->get();
        Log::info('services: ' . $services);
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
        $selectedDate = $request->input('selected_date');
        $venue = Venue::find($this->venueId);
        if (!$venue) {
            return response()->json(['error' => 'Venue not found'], 404);
        }
        $openingHours = OpeningHour::where('venue_id', $this->venueId)->pluck('id');
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
}
