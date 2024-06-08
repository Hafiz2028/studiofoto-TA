<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\ServiceEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FrontEndController extends Controller
{
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
        $data = [
            'pageTitle' => 'FotoYuk | Search Venue Page'
        ];

        if ($request->user('customer')) {
            $data['customer'] = $request->user('customer');
        }

        return view('front.pages.search', $data);
    }
    public function detailVenue(Request $request, $id)
    {
        if (Auth::guard('customer')) {
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

                foreach ($serviceEvent->printServiceEvents as $printEvent) {
                    $maxPrice += $printEvent->price;
                }
            }

            if (!$hasPackage) {
                $minPrice = 0;
                $maxPrice = 0;
            }

            $data = [
                'pageTitle' => 'FotoYuk | Detail Venue Page',
                'venue' => $venue,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
            ];
        }
        return view('front.pages.detail', $data);
    }
}
