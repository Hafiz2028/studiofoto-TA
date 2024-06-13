<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Hour;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Venue;
use App\Models\PrintPhoto;
use App\Models\VenueImage;
use App\Models\OpeningHour;
use App\Models\ServiceType;
use Illuminate\Support\Str;
use App\Models\AddOnPackage;
use App\Models\ServiceEvent;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\ServicePackage;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\Catch_;
use App\Models\PrintPhotoDetail;
use App\Models\PrintServiceEvent;
use App\Models\ServiceEventImage;
use PhpParser\Node\Stmt\TryCatch;
use App\Models\AddOnPackageDetail;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethodDetail;
use Illuminate\Support\Facades\Log;
use App\Models\ServicePackageDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
            $printPhotos = PrintPhoto::all();
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
                'time_status.*' => 'required|in:0,1,2,3',
                'prices.*' => 'required|numeric',
                'people_sums.*' => 'required|string',
            ], [
                'prices.*.required' => 'Harga paket harus diisi',
                'prices.*.numeric' => 'Harga harus dalam bentuk angka',
                'time_status.*.required' => 'Waktu Pemotretan harus diisi',
                'people_sums.*.required' => 'Total orang harus diisi',
                'people_sums.*.string' => 'Total orang harus dalam bentuk string',
            ]);
            if ($validatedData['dp_percentage'] === 'min_payment') {
                $prices = $validatedData['prices'];
                $minPaymentInput = str_replace('.', '', $request->input('min_payment_input'));
                foreach ($prices as $price) {
                    if ($minPaymentInput > $price) {
                        return redirect()->back()->with('fail', 'Nilai minimal pembayaran tidak bisa lebih besar daripada Harga Paket.');
                    }
                }
            }

            // Simpan data paket foto baru
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = new ServicePackage();
            $package->service_event_id = $serviceId;
            $package->name = $validatedData['name'];
            $package->information = $validatedData['information'];
            $dpStatus = 0;
            $dpPercentage = null;
            $dpMin = null;
            if ($validatedData['dp_percentage'] === 'dp') {
                $dpStatus = 1;
                $dpPercentage = $this->calculateDpPercentage($validatedData['dp_percentage'], $request);
            } elseif ($validatedData['dp_percentage'] === 'min_payment') {
                $dpStatus = 2;
                $dpMin = $this->calculateDpMin($request);
            }
            $package->dp_status = $dpStatus;
            $package->dp_percentage = $dpPercentage;
            $package->dp_min = $dpMin;

            $package->save();

            // Simpan service_package_details
            foreach ($validatedData['prices'] as $index => $price) {
                $packageDetail = new ServicePackageDetail();
                $packageDetail->service_package_id = $package->id;
                $packageDetail->price = $price;
                $packageDetail->time_status = $validatedData['time_status'][$index];
                $packageDetail->sum_person = $validatedData['people_sums'][$index];
                $packageDetail->save();
            }

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

            return redirect()->route('owner.venue.services.show', [$venue->id, $service->id])->with('success', 'Paket foto <b>' . $package->name . '</b> berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Gagal menambahkan paket foto. Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    private function calculateDpPercentage($dpType, $request)
    {
        if ($dpType === 'dp') {
            $request->validate([
                'dp_input' => 'required|numeric|min:1|max:100',
            ]);
            return $request->input('dp_input') / 100;
        }
        return null;
    }

    private function calculateDpMin($request)
    {
        $request->validate([
            'min_payment_input' => 'required|numeric|min:0',
        ]);
        return (int)str_replace('.', '', $request->input('min_payment_input'));
    }
    public function edit($venueId, $serviceId, $packageId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = ServicePackage::findOrFail($packageId);
            $packageDetails = ServicePackageDetail::where('service_package_id', $package->id)->get();
            $serviceTypes = ServiceType::all();
            $addOnPackages = AddOnPackage::all();
            $addOnDetail = AddOnPackageDetail::where('service_package_id', $package->id)->get();
            $printServiceEvents = PrintServiceEvent::where('service_event_id', $serviceId)->get();
            $printPhotoDetails = PrintPhotoDetail::where('service_package_id', $package->id)->get();
            $getQtyByAddOnPackageId = function ($addOnPackageId) use ($package) {
                return $package->addOnPackageDetails->where('add_on_package_id', $addOnPackageId)->first()->sum ?? '';
            };
            return view('back.pages.owner.package-manage.update', compact('venue', 'service', 'package', 'packageDetails', 'serviceTypes', 'addOnDetail', 'printPhotoDetails', 'addOnPackages', 'printServiceEvents', 'getQtyByAddOnPackageId'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function showDetail($venueId, $serviceId, $packageId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);

            $package = ServicePackage::with('printPhotoDetails.printServiceEvent.printPhoto', 'servicePackageDetails')
                ->findOrFail($packageId);

            $responseData = [
                'package' => $package,
                'packageName' => $package->name,
                'information' => $package->information,
            ];

            if ($package->printPhotoDetails->isNotEmpty()) {
                $formattedPrintPhotoDetails = $package->printPhotoDetails->map(function ($printPhotoDetail) {
                    return [
                        'size' => $printPhotoDetail->printServiceEvent->printPhoto->size,
                        'price' => $printPhotoDetail->printServiceEvent->price,
                    ];
                });
                $responseData['printPhotoDetails'] = $formattedPrintPhotoDetails;
            }

            if ($package->servicePackageDetails->isNotEmpty()) {
                $formattedServicePackageDetails = $package->servicePackageDetails->map(function ($servicePackageDetail) {
                    return [
                        'sum_person' => $servicePackageDetail->sum_person,
                        'price' => $servicePackageDetail->price,
                        'time_status' => $servicePackageDetail->time_status,
                    ];
                });
                $responseData['servicePackageDetails'] = $formattedServicePackageDetails;
            }
            return response()->json($responseData);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Package not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve package.'], 500);
        }
    }
    public function update(Request $request, $venueId, $serviceId, $packageId)
    {
        try {
            // dd($request);
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'information' => 'nullable|string',
                'dp_percentage' => 'required|in:full_payment,dp,min_payment',
                'time_status.*' => 'required|in:0,1,2,3',
                'prices.*' => 'required|numeric',
                'people_sums.*' => 'required|string',
                'new_prices.*' => 'required|numeric',
                'new_people_sums.*' => 'required|string',
                'new_time_status.*' => 'required|in:0,1,2,3',
            ], [
                'prices.*.required' => 'Harga paket harus diisi',
                'prices.*.numeric' => 'Harga harus dalam bentuk angka',
                'people_sums.*.required' => 'Total orang harus diisi',
                'people_sums.*.string' => 'Total orang harus dalam bentuk string',
                'new_prices.*.required' => 'Harga paket baru harus diisi',
                'new_prices.*.numeric' => 'Harga paket baru harus dalam bentuk angka',
                'new_people_sums.*.required' => 'Total orang untuk paket baru harus diisi',
                'new_people_sums.*.string' => 'Total orang untuk paket baru harus dalam bentuk string',
            ]);

            if ($validatedData['dp_percentage'] === 'min_payment') {
                $prices = isset($validatedData['prices']) ? $validatedData['prices'] : [];
                $newPrices = isset($validatedData['new_prices']) ? $validatedData['new_prices'] : [];
                $prices = array_merge($prices, $newPrices);

                $minPaymentInput = str_replace('.', '', $request->input('min_payment_input'));
                foreach ($prices as $price) {
                    if ($minPaymentInput > $price) {
                        return redirect()->back()->with('fail', 'Nilai minimal pembayaran tidak bisa lebih besar daripada Harga Paket.');
                    }
                }
            }
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = ServicePackage::findOrFail($packageId);
            $package->name = $validatedData['name'];
            $package->information = $validatedData['information'];
            $dpStatus = 0;
            $dpPercentage = null;
            $dpMin = null;
            if ($validatedData['dp_percentage'] === 'dp') {
                $dpStatus = 1;
                $dpPercentage = $this->calculateDpPercentage($validatedData['dp_percentage'], $request);
            } elseif ($validatedData['dp_percentage'] === 'min_payment') {
                $dpStatus = 2;
                $dpMin = $this->calculateDpMin($request);
            }
            $package->dp_status = $dpStatus;
            $package->dp_percentage = $dpPercentage;
            $package->dp_min = $dpMin;
            $package->save();

            $packageDetails = ServicePackageDetail::where('service_package_id', $package->id)->get();
            $submittedDetailIds = [];

            if (isset($validatedData['prices'])) {
                foreach ($validatedData['prices'] as $index => $price) {
                    $detailId = $packageDetails[$index]->id;
                    $detail = ServicePackageDetail::find($detailId);
                    if ($detail) {
                        $detail->update([
                            'price' => $price,
                            'sum_person' => $validatedData['people_sums'][$index],
                            'time_status' => $validatedData['time_status'][$index],
                        ]);
                        $submittedDetailIds[] = $detail->id;
                    }
                }
            }
            ServicePackageDetail::where('service_package_id', $package->id)
                ->whereNotIn('id', $submittedDetailIds)
                ->delete();

            if (isset($validatedData['new_prices'])) {
                foreach ($validatedData['new_prices'] as $index => $price) {
                    ServicePackageDetail::create([
                        'service_package_id' => $package->id,
                        'price' => $price,
                        'sum_person' => $validatedData['new_people_sums'][$index],
                        'time_status' => $validatedData['new_time_status'][$index],
                    ]);
                }
            }
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

            return redirect()->route('owner.venue.services.show', [$venue->id, $service->id])->with('success', 'Paket foto <b>' . $package->name . '</b> berhasil diperbarui.');
            // return redirect()->back()->with('success', 'paket berhasil di save.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('fail', 'Gagal memperbarui paket foto. Terjadi kesalahan: ' . $e->getMessage());
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
