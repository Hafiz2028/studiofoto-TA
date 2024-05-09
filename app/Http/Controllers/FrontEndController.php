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
        $data = [
            'pageTitle' => 'FotoYuk | Detail Venue Page',
            'venue' => $venue,
        ];
        return view('front.pages.detail', $data);
    }
}
