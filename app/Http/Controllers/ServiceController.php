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
            $serviceTypes = ServiceType::all();
            return view('back.pages.owner.service-manage.create', compact('venue', 'serviceTypes'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:3',
            'service_type_id' => 'required',
            'catalog' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000', 
        ], [
            'name.required' => 'Nama layanan wajib diisi.',
            'name.min' => 'Nama layanan minimal terdiri dari 3 karakter.',
            'service_type_id.required' => 'Jenis layanan harus dipilih.',
            'catalog.image' => 'Katalog layanan harus berupa gambar.',
            'catalog.mimes' => 'Format file katalog layanan tidak valid. Harus berupa jpeg, png, jpg, atau gif.',
            'catalog.max' => 'Ukuran file katalog layanan maksimal adalah 5000 KB.',
            'images.array' => 'Foto layanan harus berupa array.',
            'images.*.image' => 'Foto layanan harus berupa gambar.',
            'images.*.mimes' => 'Format file foto layanan tidak valid. Harus berupa jpeg, png, jpg, atau gif.',
            'images.*.max' => 'Ukuran file foto layanan maksimal adalah 5000 KB.',
        ]);
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
