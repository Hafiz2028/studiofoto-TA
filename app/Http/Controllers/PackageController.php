<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\PrintPhoto;
use App\Models\ServiceType;
use App\Models\AddOnPackage;
use App\Models\ServiceEvent;
use Illuminate\Http\Request;
use App\Models\ServicePackage;
use App\Models\FramePhotoDetail;
use App\Models\PrintPhotoDetail;
use App\Models\AddOnPackageDetail;
use Illuminate\Support\Facades\DB;
use App\Models\ServicePackageDetail;

class PackageController extends Controller
{

    public function create($venueId, $serviceId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $addOnPackages = AddOnPackage::all();
            $printPhotos = PrintPhoto::all();
            return view('back.pages.owner.package-manage.create', compact('venue', 'service', 'addOnPackages', 'printPhotos'));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }


    public function store(Request $request, $venueId, $serviceId)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'information' => 'nullable|string',
            'dp_percentage' => 'required|in:full_payment,dp,min_payment',
            'time_status.*' => 'required|in:0,1,2,3',
            'prices.*' => 'required|numeric',
            'people_sums.*' => 'required|string',
        ], [
            'name.required' => 'Nama Paket Foto harus diisi.',
            'prices.*.required' => 'Harga paket harus diisi.',
            'prices.*.numeric' => 'Harga harus dalam bentuk angka',
            'time_status.*.required' => 'Waktu Pemotretan harus diisi',
            'people_sums.*.required' => 'Total orang harus diisi',
            'people_sums.*.string' => 'Total orang harus dalam bentuk string',
        ]);
        try {
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
            if ($request->has('print_photo_details')) {
                foreach ($request->input('print_photo_details') as $printPhotoId) {
                    $printPhotoDetail = new PrintPhotoDetail();
                    $printPhotoDetail->service_package_id = $package->id;
                    $printPhotoDetail->print_photo_id = $printPhotoId;
                    $printPhotoDetail->save();
                }
            }
            if ($request->has('frame_photo_details')) {
                foreach ($request->input('frame_photo_details') as $framePhotoId) {
                    $framePhotoDetail = new FramePhotoDetail();
                    $framePhotoDetail->service_package_id = $package->id;
                    $framePhotoDetail->print_photo_id = $framePhotoId;
                    $framePhotoDetail->save();
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
            $printPhotos = PrintPhoto::all();
            $printPhotoDetails = PrintPhotoDetail::where('service_package_id', $package->id)->get();
            $framePhotoDetails = FramePhotoDetail::where('service_package_id', $package->id)->get();
            $selectedPrintPhotoIds = $printPhotoDetails->pluck('print_photo_id')->toArray();
            $selectedFramePhotoIds = $framePhotoDetails->pluck('print_photo_id')->toArray();
            $getQtyByAddOnPackageId = function ($addOnPackageId) use ($package) {
                return $package->addOnPackageDetails->where('add_on_package_id', $addOnPackageId)->first()->sum ?? '';
            };
            $data = [
                'venue' => $venue,
                'service' => $service,
                'package' => $package,
                'packageDetails' => $packageDetails,
                'serviceTypes' => $serviceTypes,
                'addOnPackages' => $addOnPackages,
                'addOnDetail' => $addOnDetail,
                'printPhotos' => $printPhotos,
                'printPhotoDetails' => $printPhotoDetails,
                'framePhotoDetails' => $framePhotoDetails,
                'selectedPrintPhotoIds' => $selectedPrintPhotoIds,
                'selectedFramePhotoIds' => $selectedFramePhotoIds,
                'getQtyByAddOnPackageId' => $getQtyByAddOnPackageId,
            ];
            return view('back.pages.owner.package-manage.update', $data);
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function showDetail($venueId, $serviceId, $packageId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = ServicePackage::with('printPhotoDetails.printPhoto', 'servicePackageDetails', 'framePhotoDetails.printPhoto', 'addOnPackageDetails.addOnPackage')
                ->findOrFail($packageId);

            $addOnDetails = $package->addOnPackageDetails->map(function ($addOnPackageDetail) {
                return  $addOnPackageDetail->sum . ' ' . $addOnPackageDetail->addOnPackage->name;
            })->implode(', ');

            $framePhotoDetails = $package->framePhotoDetails->map(function ($framePhotoDetail) {
                return 'Size ' . $framePhotoDetail->printPhoto->size;
            })->implode(', ');

            $printPhotoDetails = $package->printPhotoDetails->map(function ($printPhotoDetail) {
                return 'Size ' . $printPhotoDetail->printPhoto->size;
            })->implode(', ');

            $detailedInformation = $package->information . "\n\nDidalam paket foto ini telah include: \n" .
                ($addOnDetails ? "<b>Add-Ons</b> : $addOnDetails\n" : '') .
                ($framePhotoDetails ? "<b>Frame Foto</b> : $framePhotoDetails\n" : '') .
                ($printPhotoDetails ? "<b>Print Foto</b> : $printPhotoDetails\n" : '');

            $responseData = [
                'package' => $package,
                'packageName' => $package->name,
                'information' => $detailedInformation,
            ];

            if ($package->printPhotoDetails->isNotEmpty()) {
                $formattedPrintPhotoDetails = $package->printPhotoDetails->map(function ($printPhotoDetail) {
                    return [
                        'size' => $printPhotoDetail->printPhoto->size,
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
            if ($package->framePhotoDetails->isNotEmpty()) {
                $formattedFramePhotoDetails = $package->framePhotoDetails->map(function ($framePhotoDetail) {
                    return [
                        'size' => $framePhotoDetail->printPhoto->size,
                    ];
                });
                $responseData['framePhotoDetails'] = $formattedFramePhotoDetails;
            }
            if ($package->addOnPackageDetails->isNotEmpty()) {
                $formattedAddOnPackageDetails = $package->addOnPackageDetails->map(function ($addOnPackageDetail) {
                    return [
                        'name' => $addOnPackageDetail->addOnPackage->name,
                        'sum' => $addOnPackageDetail->sum,
                    ];
                });
                $responseData['addOnPackageDetails'] = $formattedAddOnPackageDetails;
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
            $package->printPhotoDetails()->delete();
            if ($request->has('print_photo_details')) {
                foreach ($request->input('print_photo_details') as $printPhotoId) {
                    $printPhotoDetail = new PrintPhotoDetail();
                    $printPhotoDetail->service_package_id = $package->id;
                    $printPhotoDetail->print_photo_id = $printPhotoId;
                    $printPhotoDetail->save();
                }
            }
            $package->framePhotoDetails()->delete();
            if ($request->has('frame_photo_details')) {
                foreach ($request->input('frame_photo_details') as $framePhotoId) {
                    $framePhotoDetail = new FramePhotoDetail();
                    $framePhotoDetail->service_package_id = $package->id;
                    $framePhotoDetail->print_photo_id = $framePhotoId;
                    $framePhotoDetail->save();
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
}
