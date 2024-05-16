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
                'prices.*' => 'required|numeric',
                'people_sums.*' => 'required|string',
            ], [
                'prices.*.required' => 'Harga paket harus diisi',
                'prices.*.numeric' => 'Harga harus dalam bentuk angka',
                'people_sums.*.required' => 'Total orang harus diisi',
                'people_sums.*.string' => 'Total orang harus dalam bentuk string',
            ]);
            if ($validatedData['dp_percentage'] === 'min_payment') {
                $prices = $validatedData['prices'];
                $minPaymentInputs = $request->input('min_payment_input');
                foreach ($prices as $index => $price) {
                    $minPaymentInput = $minPaymentInputs[$index];
                    if ($request->input('min_payment_input') > $price) {

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
            $package->time_status = $validatedData['time_status'];

            $package->save();

            // Simpan rincian paket foto
            foreach ($validatedData['prices'] as $index => $price) {
                $packageDetail = new ServicePackageDetail();
                $packageDetail->service_package_id = $package->id;
                $packageDetail->price = $price;
                $packageDetail->dp_status = ($validatedData['dp_percentage'] === 'dp') ? 1 : (($validatedData['dp_percentage'] === 'min_payment') ? 2 : 0);
                $packageDetail->dp_percentage = $this->saveServicePackageDetail($validatedData['dp_percentage'], $price, $request);
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

            // Redirect ke halaman yang sesuai setelah penyimpanan sukses
            return redirect()->route('owner.venue.services.show', [$venue->id, $service->id])->with('success', 'Paket foto berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika ada kesalahan, redirect kembali ke halaman sebelumnya
            return redirect()->back()->with('fail', 'Gagal menambahkan paket foto. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function saveServicePackageDetail($dpType, $price, $request)
    {
        if ($dpType === 'dp') {
            $request->validate([
                'dp_input' => 'required|numeric|min:1|max:100',
            ]);
            $dpPercentage = $request->input('dp_input') / 100;
        } elseif ($dpType === 'min_payment') {
            $request->validate([
                'min_payment_input' => 'required|numeric|min:0',
            ]);
            $minPaymentInput = str_replace('.', '', $request->input('min_payment_input'));
            $dpPercentage = $minPaymentInput / $price;
        } else {
            $dpPercentage = 0;
        }

        return $dpPercentage;
    }


    public function edit($venueId, $serviceId, $packageId)
    {
        try {
            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = ServicePackage::findOrFail($packageId);
            $serviceTypes = ServiceType::all();
            $addOnPackages = AddOnPackage::all();
            $packageDetails = ServicePackageDetail::where('service_package_id', $package->id)->get();
            $addOnDetail = AddOnPackageDetail::where('service_package_id', $package->id)->get();
            $printServiceEvents = PrintServiceEvent::where('service_event_id', $serviceId)->get();
            $printPhotoDetails = PrintPhotoDetail::where('service_package_id', $package->id)->get();
            $getQtyByAddOnPackageId = function ($addOnPackageId) use ($package) {
                return $package->addOnPackageDetails->where('add_on_package_id', $addOnPackageId)->first()->sum ?? '';
            };
            // $addedPackageDetails = [];

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
                        'dp_status' => $servicePackageDetail->dp_status,
                        'dp_percentage' => $servicePackageDetail->dp_percentage,
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
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'information' => 'nullable|string',
                'dp_percentage' => 'required|in:full_payment,dp,min_payment',
                'time_status' => 'required|in:0,1,2,3',
                'prices.*' => 'required|numeric',
                'people_sums.*' => 'required|string',
            ], [
                'prices.*.required' => 'Harga paket harus diisi',
                'prices.*.numeric' => 'Harga harus dalam bentuk angka',
                'people_sums.*.required' => 'Total orang harus diisi',
                'people_sums.*.string' => 'Total orang harus dalam bentuk string',
            ]);

            $venue = Venue::findOrFail($venueId);
            $service = ServiceEvent::findOrFail($serviceId);
            $package = ServicePackage::findOrFail($packageId);

            $package->name = $validatedData['name'];
            $package->information = $validatedData['information'];
            $package->time_status = $validatedData['time_status'];

            if ($validatedData['dp_percentage'] === 'min_payment') {
                $prices = $validatedData['prices'];
                $minPaymentInputs = $request->input('min_payment_input');
                foreach ($prices as $index => $price) {
                    $minPaymentInput = $minPaymentInputs[$index];
                    if ($request->input('min_payment_input') > $price) {

                        return redirect()->back()->with('fail', 'Nilai minimal pembayaran tidak bisa lebih besar daripada Harga Paket.');
                    }
                }
            }
            $package->save();



            DB::beginTransaction();
            $deletedQuery = ServicePackageDetail::where('service_package_id', $package->id)
                ->whereNotIn('id', $request->input('save_package_details', []));
            info('Query untuk menghapus: ' . $deletedQuery->toSql());

            $deletedDetails = $deletedQuery->get();

            foreach ($deletedDetails as $detail) {
                info('Detail yang akan dihapus: ' . $detail->id);
            }

            $deletedCount = $deletedQuery->delete();

            info('Total deleted: ' . $deletedCount);



            DB::commit();









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

            // return redirect()->route('owner.venue.services.show', [$venue->id, $service->id])->with('success', 'Paket foto berhasil diperbarui.');
            return redirect()->back()->with('success', 'paket berhasil di save.');
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
