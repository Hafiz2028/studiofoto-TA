<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Venue;
use App\Models\PaymentMethod;
use App\Models\Day;
use App\Models\Hour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\TryCatch;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $venues = Venue::all();

        return view('back.pages.owner.venue-manage.index-venue', compact('venues'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $payment_methods = PaymentMethod::all();
        $days = Day::all();
        $hours = Hour::all();
        try {
            return view('back.pages.owner.venue-manage.add-venue', compact('payment_methods', 'days', 'hours'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Venue $venue)
    {
        // try{
        //     $day = Day::pluck('name','id');
        //     $venues = Venue::find($id);
        //     $field_type = FieldType::select('name','id')->get();
        //     $openingHours = OpeningHour::select('day_id')->where('venue_id', $venues->id)->groupby('day_id')->get();

        //     Log::info("User ".Auth::user()->Owner->first_name." ".Auth::user()->Owner->last_name." Berhasil mengakses halaman detail venue pada venue");
        //     return view('backend.owner.manage_venue.show', compact('venues','field_type','id', 'openingHours','day'));
        // }
        // catch(\Exception $e){
        //     Log::error("User ".Auth::user()->Owner->first_name." ".Auth::user()->Owner->last_name." Gagal mengakses halaman detail venue pada venue");
        //     return redirect()->back();
        // }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venue $venue)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venue $venue)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venue $venue)
    {
        //
    }
}
