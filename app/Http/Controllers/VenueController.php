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
        $validatedData = $request->validate([
            'name' => 'required|min:4',
            'phone_number' => 'required|min:8|max:15',
            'information' => 'nullable|string',
            'imb' => 'required|mimes:pdf|max:5000',
            'address' => 'required|string|max:255',
            'dp_percentage' => 'nullable|numeric',
            'no_rek' => 'nullable|numeric',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'picture' => 'nullable|image|mimes:png,jpg,jpeg|max:5000',
            'venue_image' => 'nullable|image|mimes:png,jpg,jpeg|max:5000',
        ], [
            'name.required' => 'Nama venue harus diisi.',
            'name.min' => 'Nama venue minimal 4 karakter.',
            'phone_number.required' => 'Nomor HP harus diisi.',
            'phone_number.min' => 'Nomor HP minimal 8 karakter.',
            'phone_number.max' => 'Nomor HP maksimal 15 karakter.',
            'imb.required' => 'File IMB harus diunggah.',
            'imb.mimes' => 'File IMB harus berformat PDF.',
            'imb.max' => 'Ukuran file IMB maksimal 5000 KB.',
            'address.required' => 'Alamat venue harus diisi.',
            'address.max' => 'Alamat venue maksimal 255 karakter.',
            'dp_percentage.numeric' => 'DP harus berupa nilai numerik.',
            'no_rek.numeric' => 'Nomor rekening harus berupa nilai numerik.',
            'latitude.required' => 'Latitude harus diisi.',
            'latitude.numeric' => 'Latitude harus berupa nilai numerik.',
            'longitude.required' => 'Longitude harus diisi.',
            'longitude.numeric' => 'Longitude harus berupa nilai numerik.',
            'picture.image' => 'File harus berupa gambar.',
            'picture.mimes' => 'Format gambar hanya bisa PNG, JPG, dan JPEG.',
            'picture.max' => 'Ukuran gambar maksimal 5000 KB.',
            'venue_image.image' => 'File harus berupa gambar.',
            'venue_image.mimes' => 'Format gambar hanya bisa PNG, JPG, dan JPEG.',
            'venue_image.max' => 'Ukuran gambar maksimal 5000 KB.',
        ]);
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
