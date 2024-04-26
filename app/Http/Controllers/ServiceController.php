<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceEvent;
use App\Models\ServiceEventImage;
use App\Models\ServiceType;
use App\Models\ServicePackage;
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

class ServiceController extends Controller
{
    public function index()
    {
    }

    public function create($venueId)
    {

        try {
            $venue = Venue::findOrFail($venueId);
            $serviceTypes = ServiceType::all();
            return view('back.pages.owner.service-manage.create', compact('venue', 'serviceTypes'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'service_type_id' => 'required',
            'catalog' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ], [
            'name.required' => 'Nama layanan wajib diisi.',
            'name.min' => 'Nama layanan minimal terdiri dari 3 karakter.',
            'service_type_id.required' => 'Jenis layanan harus dipilih.',
            'catalog.image' => 'Paket / Katalog Foto harus berupa gambar.',
            'catalog.mimes' => 'Format file Paket / Katalog Foto tidak valid. Harus berupa jpeg, png, jpg, atau gif.',
            'catalog.max' => 'Ukuran file Paket / Katalog Foto maksimal adalah 5000 KB.',
            'images.array' => 'Foto layanan harus berupa array.',
            'images.*.image' => 'Foto layanan harus berupa gambar.',
            'images.*.mimes' => 'Format file foto layanan tidak valid. Harus berupa jpeg, png, jpg, atau gif.',
            'images.*.max' => 'Ukuran file foto layanan maksimal adalah 5000 KB.',
        ]);
        $randomNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $venueName = Venue::findOrFail($request->venue_id)->name;
        $venueName = preg_replace('/\s+/', '_', $venueName);
        $catalogFileName = null;
        $imageFileNames = [];
        if ($request->hasFile('catalog')) {
            $catalogFileName = 'MENU_' . $venueName . '_' . $randomNumber . '_' . $request->catalog->getClientOriginalName();
            $catalogPath = $request->file('catalog')->storeAs('/Katalog', $catalogFileName, 'public');
        }
        $serviceEvent = ServiceEvent::create([
            'name' => $request->name,
            'venue_id' => $request->venue_id,
            'description' => $request->description,
            'catalog' => $catalogFileName,
            'service_type_id' => $request->service_type_id,
        ]);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageFileName = 'SERVICE_' . $venueName . '_' . $randomNumber . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('/Service_Image', $imageFileName, 'public');
                $imageFileNames[] = $imageFileName;
                ServiceEventImage::create([
                    'service_event_id' => $serviceEvent->id,
                    'image' => $imageFileName,
                ]);
            }
        }
        return redirect()->route('owner.venue.show', ['venue' => $request->venue_id])->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function show($venueId, $serviceId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $serviceImages = ServiceEventImage::where('service_event_id', $serviceId)->get();
            $packages = ServicePackage::where('service_event_id', $serviceId)->get();
            return view('back.pages.owner.service-manage.show', compact('venue', 'service', 'serviceImages','packages'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('error.page')->with('error_message', 'Data tidak ditemukan.');
        } catch (\Illuminate\Contracts\View\View | \Throwable $e) {
            return redirect()->route('error.page')->with('error_message', 'Halaman tidak dapat ditampilkan: ' . $e->getMessage());
        }
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
