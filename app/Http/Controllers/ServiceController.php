<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\ServiceEvent;
use App\Models\ServiceEventImage;
use App\Models\ServiceType;
use App\Models\ServicePackage;
use App\Models\PrintPhoto;
use App\Models\PrintServiceEvent;
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
use App\Http\Controllers\Controller;

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
            $printPhotos = PrintPhoto::all();

            return view('back.pages.owner.service-manage.create', compact('venue', 'serviceTypes', 'printPhotos'));
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
        if ($request->has('print_photos')) {
            foreach ($request->print_photos as $printPhotoId) {
                $priceInputName = 'price_' . $printPhotoId;
                $price = $request->input($priceInputName);
                $price = str_replace(' ', '', $price);

                PrintServiceEvent::create([
                    'service_event_id' => $serviceEvent->id,
                    'print_photo_id' => $printPhotoId,
                    'price' => $price,
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
            $printServiceEvents = PrintServiceEvent::where('service_event_id', $serviceId)->orderBy('print_photo_id')->get();
            return view('back.pages.owner.service-manage.show', compact('venue', 'service', 'serviceImages', 'packages', 'printServiceEvents'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('error.page')->with('error_message', 'Data tidak ditemukan.');
        } catch (\Illuminate\Contracts\View\View | \Throwable $e) {
            return redirect()->route('error.page')->with('error_message', 'Halaman tidak dapat ditampilkan: ' . $e->getMessage());
        }
    }
    public function edit($venueId, $serviceId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $serviceTypes = ServiceType::all();
            $printPhotos = PrintPhoto::all();
            $printServiceEvents = PrintServiceEvent::where('service_event_id', $service->id)->get();
            $serviceEventImages = ServiceEventImage::where('service_event_id', $service->id)->get();
            session()->put('temp_catalog', $service->catalog);
            return view('back.pages.owner.service-manage.update', compact('venue', 'serviceTypes', 'printPhotos', 'service', 'printServiceEvents', 'serviceEventImages'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function update(Request $request, $venueId, $serviceId)
    {
        try {
            // Validasi data yang dikirimkan melalui formulir
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'service_type_id' => 'required|exists:service_types,id',
                'description' => 'nullable|string',
                'prices.*' => 'required|numeric',
            ]);
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $oldData = $service->toArray();
            $service->name = $validatedData['name'];
            $service->service_type_id = $validatedData['service_type_id'];
            $service->description = $validatedData['description'];


            if ($request->has('print_photos_switch')) {
                $print_photos_switch = $request->input('print_photos_switch');
                if ($print_photos_switch) {
                    $printPhotos = $request->input('print_photos', []);
                    $prices = $request->input('prices', []);

                    // Ambil semua print photos yang sudah ada untuk service event ini
                    $existingPrintPhotos = PrintServiceEvent::where('service_event_id', $service->id)
                        ->pluck('print_photo_id')
                        ->toArray();

                    foreach ($printPhotos as $printPhotoId) {
                        if (isset($prices[$printPhotoId])) {
                            $price = $prices[$printPhotoId];
                            PrintServiceEvent::updateOrCreate(
                                ['print_photo_id' => $printPhotoId, 'service_event_id' => $service->id],
                                ['price' => $price]
                            );
                        } else {
                            if (in_array($printPhotoId, $existingPrintPhotos)) {
                                PrintServiceEvent::where('print_photo_id', $printPhotoId)
                                    ->where('service_event_id', $service->id)
                                    ->delete();
                            } else {
                                dd("Harga tidak valid atau tidak ditemukan untuk Print Photo ID: $printPhotoId");
                            }
                        }
                    }
                    PrintServiceEvent::where('service_event_id', $service->id)
                        ->whereNotIn('print_photo_id', $printPhotos)
                        ->delete();
                } else {
                    dd('Data cetak foto akan dihapus');
                }
            } else {
                PrintServiceEvent::where('service_event_id', $service->id)->delete();
            }
            if ($request->hasFile('new_catalog')) {
                if ($service->catalog) {
                    $catalogPath = public_path('/images/venues/Katalog/' . $service->catalog);
                    if (File::exists($catalogPath)) {
                        File::delete($catalogPath);
                    }
                }
                // Simpan katalog baru
                $catalogFileName = 'MENU_' . $service->name . '_' . $service->id . '_' . time() . '_' . $request->new_catalog->getClientOriginalName();
                $request->new_catalog->storeAs('/Katalog', $catalogFileName, 'public');
                $service->catalog = $catalogFileName;
            }
            if ($request->has('deletedImageIds')) {
                $deletedImageIds = json_decode($request->input('deletedImageIds'));
                if (!empty($deletedImageIds)) {
                    $imagesToDelete = ServiceEventImage::whereIn('id', $deletedImageIds)->pluck('image')->toArray();
                    ServiceEventImage::whereIn('id', $deletedImageIds)->delete();
                    foreach ($imagesToDelete as $imageName) {
                        $imagePath = public_path('images/venues/Service_Image/' . $imageName);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                }
            }
            // Proses gambar
            if ($request->hasFile('image_venue')) {
                foreach ($request->file('image_venue') as $image) {
                    $imageFileName = 'SERVICE_' . $service->id . '_' . time() . '_' . $image->getClientOriginalName();
                    $image->storeAs('/Service_Image', $imageFileName, 'public');
                    $serviceEventImage = ServiceEventImage::where('service_event_id', $service->id)
                        ->where('image', $imageFileName)
                        ->first();
                    if ($serviceEventImage) {
                        $serviceEventImage->update(['image' => $imageFileName]);
                    } else {
                        ServiceEventImage::create([
                            'service_event_id' => $service->id,
                            'image' => $imageFileName
                        ]);
                    }
                }
            }
            $service->save();
            $newData = $service->toArray();
            $updatedData = array_diff_assoc($newData, $oldData);
            Log::info('Data updated: ' . json_encode($updatedData));
            return redirect()->route('owner.venue.services.show', [$venue->id, $service->id])->with('success', 'Layanan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui layanan: ' . $e->getMessage()); // Log pesan kesalahan
            return redirect()->back()->with('error', 'Gagal memperbarui layanan. Silakan coba lagi. Pesan Kesalahan: ' . $e->getMessage());
        }
    }
    public function destroy($venueId, $serviceId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $service->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
