<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceEvent;
use App\Models\ServiceEventImage;
use App\Models\ServiceType;
use App\Models\ServicePackage;
use App\Models\AddOnPackage;
use App\Models\AddOnPackageDetail;
use App\Models\PrintPhoto;
use App\Models\PrintServiceEvent;
use App\Models\PrintPhotoDetail;
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

class PackageController extends Controller
{

    public function index($venueId, $serviceId)
    {
    }


    public function create($venueId, $serviceId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $addOnPackages = AddOnPackage::all();
            $printServiceEvents = PrintServiceEvent::where('service_event_id', $serviceId)->get();
            return view('back.pages.owner.package-manage.create', compact('venue', 'service', 'addOnPackages', 'printServiceEvents'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }


    public function store(Request $request, $venueId, $serviceId)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'information' => 'nullable|string',
                'dp_percentage' => 'required|in:full_payment,dp,min_payment',
                'time_status' => 'required|in:0,1,2,3',
                'price' => 'required|numeric',
            ], [
                'price.numeric' => 'Harga Harus dalam bentuk angka'
            ]);

            // Simpan data paket foto baru
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = new ServicePackage();
            $package->service_event_id = $serviceId;
            $package->name = $validatedData['name'];
            $package->information = $validatedData['information'];
            $package->time_status = $validatedData['time_status'];
            $price = str_replace(' ', '', $validatedData['price']);
            $package->price = $price;
            $package->dp_status = ($validatedData['dp_percentage'] === 'dp') ? 1 : (($validatedData['dp_percentage'] === 'min_payment') ? 2 : 0);

            if ($validatedData['dp_percentage'] === 'dp') {
                $request->validate([
                    'dp_input' => 'required|numeric|min:1|max:100',
                ]);

                $dpPercentage = $request->input('dp_input') / 100;
                $package->dp_percentage = $dpPercentage;
            } elseif ($validatedData['dp_percentage'] === 'min_payment') {
                $request->validate([
                    'min_payment_input' => 'required|numeric|min:0',
                ]);
                $minPaymentInput = str_replace('.', '', $request->input('min_payment_input'));
                $minPaymentAmount = $minPaymentInput / $price;
                $package->dp_percentage = $minPaymentAmount;
            } else {
                $package->dp_percentage = 0;
            }

            $package->save();

            // Simpan data add-ons jika ditambahkan
            if ($request->has('add_ons')) {
                foreach ($request->input('add_ons') as $addOnId) {
                    $totalQty = $request->input('total_qty_' . $addOnId);
                    if ($totalQty > 0) {
                        $addOnDetail = new AddOnPackageDetail();
                        $addOnDetail->service_package_id = $package->id;
                        $addOnDetail->add_on_package_id = $addOnId;
                        $addOnDetail->sum = $totalQty;
                        $addOnDetail->save();
                    }
                }
            }

            // Simpan data ukuran cetak foto jika dipilih
            if ($request->has('print_photos')) {
                foreach ($request->input('print_photos') as $printPhotoId) {
                    $printPhotoDetail = new PrintPhotoDetail();
                    $printPhotoDetail->service_package_id = $package->id;
                    $printPhotoDetail->print_service_event_id = $printPhotoId;
                    $printPhotoDetail->save();
                }
            }

            // Redirect ke halaman yang sesuai setelah penyimpanan sukses
            return redirect()->route('owner.venue.services.show', [$venue->id, $service->id])->with('success', 'Paket foto berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika ada kesalahan, redirect kembali ke halaman sebelumnya
            return redirect()->back()->with('fail', 'Gagal menambahkan paket foto. Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function edit($venueId, $serviceId, $packageId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = ServicePackage::findOrFail($packageId);
            $serviceTypes = ServiceType::all();
            $addOnPackages = AddOnPackage::all();
            $addOnDetail = AddOnPackageDetail::where('service_package_id', $package->id)->get();
            $printServiceEvents = PrintServiceEvent::where('service_event_id', $serviceId)->get();
            $printPhotoDetails = PrintPhotoDetail::where('service_package_id', $package->id)->get();
            $getQtyByAddOnPackageId = function ($addOnPackageId) use ($package) {
                return $package->addOnPackageDetails->where('add_on_package_id', $addOnPackageId)->first()->sum ?? '';
            };
            return view('back.pages.owner.package-manage.update', compact('venue', 'service', 'package', 'serviceTypes', 'addOnDetail', 'printPhotoDetails', 'addOnPackages', 'printServiceEvents', 'getQtyByAddOnPackageId'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }


    public function update(Request $request, $venueId, $serviceId, $packageId)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'information' => 'nullable|string',
                'dp_percentage' => 'required|in:full_payment,dp,min_payment',
                'time_status' => 'required|in:0,1,2,3',
                'price' => 'required|numeric',
            ], [
                'price.numeric' => 'Harga Harus dalam bentuk angka'
            ]);

            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = ServicePackage::findOrFail($packageId);

            $package->name = $validatedData['name'];
            $package->information = $validatedData['information'];
            $package->time_status = $validatedData['time_status'];
            $price = str_replace(' ', '', $validatedData['price']);
            $package->price = $price;
            $package->dp_status = ($validatedData['dp_percentage'] === 'dp') ? 1 : (($validatedData['dp_percentage'] === 'min_payment') ? 2 : 0);


            if ($validatedData['dp_percentage'] === 'dp') {
                $request->validate([
                    'dp_input' => 'required|numeric|min:1|max:100',
                ]);

                $dpPercentage = $request->input('dp_input') / 100;
                $package->dp_percentage = $dpPercentage;
            } elseif ($validatedData['dp_percentage'] === 'min_payment') {
                $request->validate([
                    'min_payment_input' => 'required|numeric|min:0',
                ]);
                $minPaymentInput = str_replace('.', '', $request->input('min_payment_input'));
                $minPaymentAmount = $minPaymentInput / $price;
                $package->dp_percentage = $minPaymentAmount;
            } else {
                $package->dp_percentage = 0;
            }

            $package->save();

            // Update data add-ons
            $package->addOnPackageDetails()->delete();

            if ($request->has('add_ons')) {
                foreach ($request->input('add_ons') as $addOnId) {
                    $totalQty = $request->input('total_qty_' . $addOnId);
                    if ($totalQty > 0) {
                        $addOnDetail = new AddOnPackageDetail();
                        $addOnDetail->service_package_id = $package->id;
                        $addOnDetail->add_on_package_id = $addOnId;
                        $addOnDetail->sum = $totalQty;
                        $addOnDetail->save();
                    }
                }
            }

            // Update data ukuran cetak foto
            $package->printPhotoDetails()->delete(); // Hapus semua detail cetak foto terkait dengan paket ini

            if ($request->has('print_photos')) {
                foreach ($request->input('print_photos') as $printPhotoId) {
                    $printPhotoDetail = new PrintPhotoDetail();
                    $printPhotoDetail->service_package_id = $package->id;
                    $printPhotoDetail->print_service_event_id = $printPhotoId;
                    $printPhotoDetail->save();
                }
            }

            return redirect()->route('owner.venue.services.show', [$venue->id, $service->id])->with('success', 'Paket foto berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Gagal memperbarui paket foto. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showDetail($venueId, $serviceId, $packageId)
    {
        try {
            $package = ServicePackage::with('printPhotoDetails.printServiceEvent.printPhoto')->findOrFail($packageId);
            $formattedPrintPhotoDetails = $package->printPhotoDetails->map(function ($printPhotoDetail) {
                return [
                    'size' => $printPhotoDetail->printServiceEvent->printPhoto->size,
                    'price' => $printPhotoDetail->printServiceEvent->price,
                ];
            });
            return response()->json([
                'package' => $package,
                'printPhotoDetails' => $formattedPrintPhotoDetails,
                'information' => $package->information, // Include the 'information' field
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Package not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve package.'], 500);
        }
    }

    public function destroy($venueId, $serviceId, $packageId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = ServicePackage::findOrFail($packageId);
            $package->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function show($venueId, $serviceId, $packageId)
    {
    }
}
