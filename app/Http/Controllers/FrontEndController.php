<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\ServiceEvent;
use Illuminate\Http\Response;

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
        $venue = Venue::findOrFail($id);
        $minPrice = PHP_INT_MAX;
        $maxPrice = 0;
        foreach ($venue->serviceEvents as $serviceEvent) {
            foreach ($serviceEvent->servicePackages as $package) {
                $minPrice = min($minPrice, $package->price);
                $maxPrice = max($maxPrice, $package->price);
            }
            foreach ($serviceEvent->printServiceEvents as $printEvent) {
                $maxPrice += $printEvent->price;
            }
        }
        $data = [
            'pageTitle' => 'FotoYuk | Detail Venue Page',
            'venue' => $venue,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ];
        return view('front.pages.detail', $data);
    }
}
