<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venue;
use App\Models\ServiceEvent;

class FrontEndController extends Controller
{
    public function homePage(Request $request){

        $data = [
            'pageTitle' => 'FotoYuk | Home Page'
        ];
        return view('front.pages.home',$data);
    }
    public function searchPage(Request $request){
        $data = [
            'pageTitle' => 'FotoYuk | Search Venue Page'
        ];
        return view('front.pages.search',$data);
    }
    public function detailVenue(Request $request){
        $data = [
            'pageTitle' => 'FotoYuk | Search Venue Page'
        ];
        return view('front.pages.search',$data);
    }
}
