<?php

namespace App\Livewire\Venue;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethodDetail;
use Livewire\WithFileUploads;
use App\Models\Venue;
use App\Models\PaymentMethod;
use App\Models\VenueImage;
use App\Models\Day;
use App\Models\Hour;
use App\Models\District;
use App\Models\Village;
use App\Models\OpeningHour;
use Illuminate\Http\UploadedFile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AddVenueTabs extends Component
{
    use WithFileUploads;
    public $venue;

    public $name, $phone_number, $information, $imb, $address, $village_id, $map_link;
    public $selectedDistrictId, $villages = [];

    // variable step 3
    public $selectedPaymentMethod = [];
    public $bank_accounts = [];
    public $payment_methods;

    // variable step 4
    public $selectedOpeningDay = [];
    public $opening_hours = [];
    public $days, $hours;

    public $venueImages = [];
    public $upload = [];
    public $deletedVenueImageIndices = [];

    public $errorBag = null;
    public $totalSteps = 5;
    public $currentStep = 1;

    public function render()
    {
        $validationErrors = [];
        $districts = District::all();
        if ($this->currentStep == 3) {
            $validationErrors = $this->getErrorBag()->get('bank_accounts.*');
        }
        return view('livewire.venue.add-venue-tabs', [
            'validationErrors' => $validationErrors,
            'districts' => $districts,
        ]);
    }

    public function mount(Venue $venue = null)
    {
        $user = Auth::user();
        if ($user && $user->role === 'owner') {
            $this->venue = $venue;
            $this->payment_methods = PaymentMethod::all();
            $this->days = Day::all();
            $this->hours = Hour::all();
            if ($this->venue && $this->venue->exists) {
                $this->name = $this->venue->name;
                $this->phone_number = $this->venue->phone_number;
                $this->information = $this->venue->information;
                $this->imb = $this->venue->imb;
                $this->address = $this->venue->address;
                $this->village_id = $this->venue->village_id;
                $this->map_link = $this->venue->map_link;
                $venueImages = $this->venue->venueImages()->select('id', 'image')->get();
                $this->venueImages = $venueImages->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'name' => $image->image,
                    ];
                })->toArray();
                $this->initializeSelectedPaymentMethods();
                foreach ($this->venue->paymentMethodDetails as $paymentMethodDetail) {
                    $this->bank_accounts[$paymentMethodDetail->payment_method_id] = $paymentMethodDetail->no_rek;
                    $this->selectedPaymentMethod[$paymentMethodDetail->payment_method_id] = true;
                }
                foreach ($this->days as $day) {
                    $openingHourStatus = $this->venue->openingHours()->where('day_id', $day->id)->where('status', 2)->exists();
                    $this->selectedOpeningDay[$day->id] = $openingHourStatus;
                    $this->opening_hours[$day->id] = [];
                    foreach ($this->hours as $hour) {
                        $openingHourStatus = $this->venue->openingHours()->where('day_id', $day->id)->where('hour_id', $hour->id)->value('status');
                        $this->opening_hours[$day->id][$hour->id] = $openingHourStatus === 2; // Checkbox dicentang jika status 2
                    }
                }
                $village = Village::with('district')->find($this->village_id);
                if ($village) {
                    $this->selectedDistrictId = $village->district->id;
                }
            } else {
                $this->venue = new Venue();
                $this->initializeSchedules();
                $this->initializeSelectedPaymentMethods();
                $this->bank_accounts = [];
                $this->village_id = null;
            }
            $this->getVillages();
            $this->errorBag = null;
        }
    }

    public function getVillages()
    {
        if (!empty($this->selectedDistrictId)) {
            $this->villages = Village::where('district_id', $this->selectedDistrictId)->get();
        } else {
            $this->villages = [];
        }
    }
    public function updatedSelectedDistrictId($value)
    {
        $this->getVillages();
    }
    public function saveVillageId($villageId)
    {
        $this->village_id = $villageId;
    }

    //inisialisasi false
    protected function initializeSchedules()
    {
        if (!$this->venue) {
            foreach ($this->days as $day) {
                $this->selectedOpeningDay[$day->id] = false; // Inisialisasi status hari sebagai false
                $this->opening_hours[$day->id] = [];
                foreach ($this->hours as $hour) {
                    $this->opening_hours[$day->id][$hour->id] = false; // Inisialisasi status jam sebagai false
                }
            }
        }
        // Tambahkan kondisi berikut untuk mengatur opening_hours menjadi false saat kondisi create venue
        else {
            foreach ($this->days as $day) {
                $this->selectedOpeningDay[$day->id] = $this->venue->openingHours()->where('day_id', $day->id)->exists();
                $this->opening_hours[$day->id] = [];
                foreach ($this->hours as $hour) {
                    $this->opening_hours[$day->id][$hour->id] = false; // Inisialisasi sebagai false
                }
            }
        }
    }
    protected function getOpeningHoursData($venue)
    {
        $openingHoursData = [];

        $openingHours = OpeningHour::where('venue_id', $venue->id)->get();

        foreach ($openingHours as $openingHour) {
            $dayId = $openingHour->day_id;
            $hourId = $openingHour->hour_id;

            if (!isset($openingHoursData[$dayId])) {
                $openingHoursData[$dayId] = [];
            }

            $openingHoursData[$dayId][] = $hourId;
        }

        return $openingHoursData;
    }
    protected function initializeDaySchedule($dayId)
    {
        if ($this->venue) {
            $openingHours = $this->venue->openingHours()->where('day_id', $dayId)->get();
            $this->opening_hours[$dayId] = [];

            foreach ($this->hours as $hour) {
                $hourId = $hour->id;
                $openingHourExists = $openingHours->contains('hour_id', $hourId);
                $this->opening_hours[$dayId][$hourId] = $openingHourExists;
            }
        }
    }
    //ubah status check box jadwal hari
    public function toggleDaySchedule($dayId)
    {
        if (isset($this->selectedOpeningDay[$dayId])) {
            $this->selectedOpeningDay[$dayId] = !$this->selectedOpeningDay[$dayId];
        } else {
            $this->selectedOpeningDay[$dayId] = true;
        }

        if (!$this->selectedOpeningDay[$dayId]) {
            foreach ($this->hours as $hour) {
                $this->opening_hours[$dayId][$hour->id] = false;
            }
        } else {
        }

        $this->validateOpeningHours();
    }
    public function validateOpeningHours()
    {
        $hasActiveDays = false;

        foreach ($this->selectedOpeningDay as $dayId => $isSelected) {
            if ($isSelected) {
                $hasActiveDays = true;
                break;
            }
        }

        if (!$hasActiveDays) {
            $this->addError('opening_hours_validation', 'Harap pilih minimal satu hari untuk jadwal operasional.');
        } else {
            $this->resetErrorBag('opening_hours_validation');
        }
    }
    protected function initializeSelectedPaymentMethods()
    {
        if ($this->payment_methods) {
            foreach ($this->payment_methods as $payment_method) {
                $this->selectedPaymentMethod[$payment_method->id] = false;
            }
        }
    }
    public function toggleBankAccountInput($payment_method_id)
    {
        if (!isset($this->selectedPaymentMethod[$payment_method_id])) {
            $this->selectedPaymentMethod[$payment_method_id] = true;
        } else {
            $this->selectedPaymentMethod[$payment_method_id] = !$this->selectedPaymentMethod[$payment_method_id];
        }

        // Tampilkan atau sembunyikan input bank_account berdasarkan status checkbox
        if ($this->selectedPaymentMethod[$payment_method_id]) {
            $this->bank_accounts[$payment_method_id] = '';
        } else {
            unset($this->bank_accounts[$payment_method_id]);
        }
    }
    public function selectedPaymentMethod($value, $payment_method_id)
    {
        if ($value) {
            $this->selectedPaymentMethod[$payment_method_id] = true;
        } else {
            $this->selectedPaymentMethod[$payment_method_id] = false;
            // Reset bank account value when unchecking the checkbox
            $this->bank_accounts[$payment_method_id] = '';
        }
    }
    public function updatedBankAccounts($value, $paymentMethodId)
    {
        $this->resetErrorBag("bank_accounts.{$paymentMethodId}");
    }
    public function updatedSelectedPaymentMethod()
    {
        foreach ($this->selectedPaymentMethod as $paymentMethodId => $isSelected) {
            $this->resetErrorBag("bank_accounts.{$paymentMethodId}");
        }
    }
    public function increaseStep()
    {
        $this->resetErrorBag();
        if (!$this->validationData()) {
            return;
        }
        if ($this->currentStep == 4) {
            $this->validateOpeningHours();
            if (!$this->getErrorBag()->isEmpty()) {
                // Jika terdapat kesalahan validasi, hentikan peningkatan langkah
                return;
            }
        }
        $this->currentStep++;
        if ($this->currentStep > $this->totalSteps) {
            $this->currentStep = $this->totalSteps;
        }
    }
    public function decreaseStep()
    {
        $this->resetErrorBag();
        // $this->validationData();
        $this->currentStep--;
        if ($this->currentStep < 1) {
            $this->currentStep = 1;
        }
    }
    //ubah status check box jadwal jam
    public function toggleHourSchedule($dayId, $hourId)
    {
        if (array_key_exists($dayId, $this->opening_hours) && array_key_exists($hourId, $this->opening_hours[$dayId])) {
            $this->opening_hours[$dayId][$hourId] = !$this->opening_hours[$dayId][$hourId];
        } else {
            Log::error("Undefined array key: {$hourId} for day {$dayId}");
            session()->flash('error_message', 'Terjadi kesalahan dalam memproses jadwal Jam Anda.');
        }
        $this->validateOpeningHours();
    }
    //mengatur value selectedOpeningDay
    public function selectedOpeningDay($value, $dayId)
    {
        if (isset($this->selectedOpeningDay[$dayId])) {
            $this->selectedOpeningDay[$dayId] = $value;
        } else {
            $this->selectedOpeningDay[$dayId] = false;
            $this->opening_hours[$dayId] = '';
        }
    }
    //mengatur value
    public function selectedOpeningHours($value, $dayId, $hourId)
    {
        if (isset($this->opening_hours[$dayId][$hourId])) {
            $this->opening_hours[$dayId][$hourId] = $value;
        } else {
            Log::error("Undefined array key: {$hourId} for day {$dayId}");
            session()->flash('error_message', 'Terjadi kesalahan dalam memproses jadwal jam Anda.');
        }
    }
    //tombol checkall
    public function checkAll($dayId)
    {
        if (!isset($this->opening_hours[$dayId])) {
            $this->opening_hours[$dayId] = [];
        }
        $allChecked = true;

        // Memeriksa apakah setiap jam sudah dicentang
        foreach ($this->hours as $hour) {
            $hourId = $hour->id;
            if (!isset($this->opening_hours[$dayId][$hourId]) || !$this->opening_hours[$dayId][$hourId]) {
                $allChecked = false;
                break;
            }
        }

        // Jika belum semua dicentang, centang semua jam
        if (!$allChecked) {
            foreach ($this->hours as $hour) {
                $hourId = $hour->id;
                $this->opening_hours[$dayId][$hourId] = true;
            }
        } else {
            // Jika semua sudah dicentang, hapus ceklis semua jam
            foreach ($this->hours as $hour) {
                $hourId = $hour->id;
                $this->opening_hours[$dayId][$hourId] = false;
            }
        }
    }
    //tombol uncheckall
    public function uncheckAll($dayId)
    {
        if (isset($this->opening_hours[$dayId])) {
            foreach ($this->opening_hours[$dayId] as $hourId => $value) {
                $this->opening_hours[$dayId][$hourId] = false;
            }
        }
    }
    //tombol checkworkinghours
    public function checkWorkingHours($dayId)
    {
        // Memastikan jadwal_jam untuk hari ini terinisialisasi
        if (!isset($this->selectedOpeningDay[$dayId])) {
            $this->selectedOpeningDay[$dayId] = [];
        }

        // Iterasi semua jam dan ceklis jika belum dicentang, dan uncheck jika sudah tercentang
        if (!isset($this->opening_hours[$dayId])) {
            $this->opening_hours[$dayId] = [];
        }

        // Iterasi semua jam dan ceklis jika belum dicentang, dan uncheck jika sudah tercentang
        foreach ($this->hours as $hour) {
            $hourId = $hour->id;
            if ($hourId >= 16 && $hourId <= 45) {
                // Check jam jika belum dicentang
                $this->opening_hours[$dayId][$hourId] = true;
            } else {
                // Uncheck jam jika sudah dicentang
                $this->opening_hours[$dayId][$hourId] = false;
            }
        }
    }
    //tombol copy schedule
    public function copySchedule($currentDayId, $nextDayId)
    {
        // Pastikan ada jadwal untuk hari ini yang akan disalin
        if (isset($this->opening_hours[$currentDayId])) {
            // Salin jadwal jam dari hari ini ke hari berikutnya
            foreach ($this->opening_hours[$currentDayId] as $hourId => $isChecked) {
                // Pastikan bahwa $nextDayId ada dalam array $this->opening_hours
                // Jika tidak, inisialisasi array untuk $nextDayId
                if (!isset($this->opening_hours[$nextDayId])) {
                    $this->opening_hours[$nextDayId] = [];
                }
                // Salin nilai dari $currentDayId ke $nextDayId dan atur menjadi true
                $this->opening_hours[$nextDayId][$hourId] = $isChecked;
            }
        }

        if (!isset($this->toggleDaySchedule[$nextDayId]) || !$this->selectedOpeningDay[$nextDayId]) {
            $this->selectedOpeningDay[$nextDayId] = true;
        }
    }
    public function updatedSelectedOpeningHours($dayId, $hourId, $value)
    {
        if ($value) {
            $this->opening_hours[$dayId][$hourId] = true;
        } else {
            $this->opening_hours[$dayId][$hourId] = false;
        }
    }
    //validasi persteps
    public function validationData()
    {
        $rules = [];
        $messages = [];
        if ($this->currentStep == 1) {
            $rules = [
                'name' => 'required|min:2',
                'phone_number' => 'required|min:8|max:15',
                'information' => 'nullable|string',
            ];
            $messages = [
                'name.required' => 'Isi nama venue anda.',
                'name.min' => 'Nama Venue minimal 2 karakter.',
                'phone_number.required' => 'Nomor telepon owner harus diisi.',
                'phone_number.min' => 'Nomor telepon minimal harus 8 angka.',
                'phone_number.max' => 'Nomor telepon maksimal harus 15 angka.',
            ];
            if ($this->venue->imb) {
                $rules['imb'] = 'nullable';
            } else {
                $rules['imb'] = 'required|mimes:pdf|max:2048';
                $messages['imb.required'] = 'File IMB harus diunggah.';
                $messages['imb.mimes'] = 'File IMB harus berupa file PDF.';
                $messages['imb.max'] = 'Ukuran file IMB maksimal adalah 2 MB.';
                if ($this->imb && $this->imb->isValid()) {
                    $originalName = $this->imb->getClientOriginalName();
                } else {
                    $this->validate($rules, $messages);
                    return;
                }
                $venueName = $this->name;
                $newImbName = 'IMB_' . $venueName . '_' . $originalName;
                try {
                    $upload_imb = $this->imb->storeAs('/IMB', $newImbName, 'public');
                    session(['imb_path' => $newImbName]);
                } catch (\Exception $e) {
                    $this->addError('imb', 'Gagal menyimpan file IMB: ' . $e->getMessage());
                    return;
                }
            }
        } elseif ($this->currentStep == 2) {
            $rules = [
                'address' => 'required|string|max:255',
                'village_id' => 'required',
                'map_link' => 'required',
            ];
            $messages = [
                'address.required' => 'Alamat Venue harus diisi.',
                'address.string' => 'Alamat venue harus berupa teks.',
                'address.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
                'village_id.required' => 'Kelurahan venue harus diisi.',
                'map_link.required' => 'Link Lokasi Venue harus diisi.',
            ];
        } elseif ($this->currentStep == 3) {

            $isAnyBankAccountFilled = false;
            foreach ($this->selectedPaymentMethod as $paymentMethodId => $isSelected) {
                if ($isSelected) {
                    $rules["bank_accounts.{$paymentMethodId}"] = 'required';
                    $messages["bank_accounts.{$paymentMethodId}.required"] = 'Nomor Rekening / E-Wallet harus diisi.';
                    if (!empty($this->bank_accounts[$paymentMethodId])) {
                        $isAnyBankAccountFilled = true;
                    }
                }
            }
            if (!$isAnyBankAccountFilled) {

                $rules['bank_accounts'] = 'required';
                $messages['bank_accounts.required'] = 'Minimal Ceklis dan isi 1 rekening bank / e wallet.';
            }
        } elseif ($this->currentStep == 4) {
            $hasAtLeastOneDaySelected = false;
            foreach ($this->opening_hours as $dayId => $hours) {
                $dayHasAtLeastOneOpeningHourSelected = false;
                foreach ($hours as $hourId => $isSelected) {
                    if ($isSelected) {
                        $dayHasAtLeastOneOpeningHourSelected = true;
                        $hasAtLeastOneDaySelected = true;
                        break; // Keluar dari loop saat menemukan satu yang terpilih
                    }
                }
                // Jika tidak ada jam yang terpilih untuk hari ini, tambahkan aturan validasi dan pesan error
                if ($this->selectedOpeningDay[$dayId]) {
                    $rules["opening_hours.{$dayId}.*"] = 'required';
                    $messages["opening_hours.{$dayId}.*.required"] = "Minimal Ceklis satu jam operasional untuk Hari " . Day::find($dayId)->name . " yang telah dibuka";
                }
            }
            if (!$hasAtLeastOneDaySelected) {
                $rules["opening_hours"] = 'required';
                $messages["opening_hours.required"] = "Pilih Minimal Satu Jadwal Hari dan Satu Jadwal Operasional Venue.";
            }
        }
        // dd($this->village_id);
        // dd($rules, $messages);
        // dd($this->opening_hours);

        $this->validate($rules, $messages);
        return true;
    }
    public function saveOpeningHours($venue)
    {
        try {
            $daysWithSelectedHours = [];
            foreach ($this->opening_hours as $dayId => $hours) {
                if (in_array(true, $hours)) {
                    $daysWithSelectedHours[] = $dayId;
                }
            }

            foreach ($daysWithSelectedHours as $dayId) {
                foreach ($this->opening_hours[$dayId] as $hourId => $isChecked) {
                    $status = $isChecked ? 2 : 1;

                    // Check if the opening hour already exists
                    $existingOpeningHour = OpeningHour::where('venue_id', $venue->id)
                        ->where('day_id', $dayId)
                        ->where('hour_id', $hourId)
                        ->first();

                    if ($existingOpeningHour) {
                        // If it exists, update the status
                        $existingOpeningHour->status = $status;
                        $existingOpeningHour->save();
                    } else {
                        // If it doesn't exist, create a new entry
                        $openingHour = new OpeningHour();
                        $openingHour->status = $status;
                        $openingHour->venue_id = $venue->id;
                        $openingHour->day_id = $dayId;
                        $openingHour->hour_id = $hourId;
                        $openingHour->save();
                    }
                }
            }
            OpeningHour::where('venue_id', $venue->id)
                ->whereNotIn('day_id', array_keys($this->opening_hours))
                ->delete();
            foreach ($this->opening_hours as $dayId => $hours) {
                if (!in_array(true, $hours)) {
                    OpeningHour::where('venue_id', $venue->id)
                        ->where('day_id', $dayId)
                        ->delete();
                }
            }
        } catch (\Exception $e) {
            $this->addError('save_opening_hours', 'Gagal menyimpan jadwal buka: ' . $e->getMessage());
            dd('Failed to save opening hours: ' . $e->getMessage());
        }
    }
    public function saveBankAccountDetails($venue)
    {
        try {
            $venueId = $venue->id;
            foreach ($this->selectedPaymentMethod as $paymentMethodId => $isChecked) {
                if ($isChecked && isset($this->bank_accounts[$paymentMethodId])) {
                    PaymentMethodDetail::updateOrCreate(
                        [
                            'payment_method_id' => $paymentMethodId,
                            'venue_id' => $venueId,
                        ],
                        ['no_rek' => $this->bank_accounts[$paymentMethodId]]
                    );
                } else {
                    PaymentMethodDetail::where('payment_method_id', $paymentMethodId)
                        ->where('venue_id', $venueId)
                        ->delete();
                }
            }
            // dd('Bank account details saved successfully.');
        } catch (\Exception $e) {
            $this->addError('save_bank_account_details', $e->getMessage());
            dd('Failed to save bank account details: ' . $e->getMessage());
        }
    }
    protected function validateVenueImages()
    {
        $rules = [
            'venueImages.*' => 'required|image|mimes:png,jpg,jpeg|max:5000',
        ];
        $messages = [
            'venueImages.*.image' => 'Foto Studio Venue harus berupa file gambar.',
            'venueImages.*.mimes' => 'Format gambar yang diperbolehkan hanya PNG, JPG, atau JPEG.',
            'venueImages.*.max' => 'Ukuran file Foto Studio Venue maksimal adalah 5 MB.',
        ];
        $this->validate($rules, $messages);
    }
    public function updatedVenueImages($value, $index)
    {
        if (!empty($value)) {
            $this->validate([
                'venueImages.' . $index => 'image|max:1024',
            ]);
        }
    }
    protected function saveVenueData($venue)
    {
        try {
            $this->saveBankAccountDetails($venue);
            $this->saveOpeningHours($venue);
            $this->saveVenueImages($venue);
            return $venue;
        } catch (\Exception $e) {
            $this->addError('venue_creation', $e->getMessage());
            return null;
        }
    }
    public function addImage()
    {
        $this->venueImages[] = null;
        // Log::info('Image added:', ['venueImages' => $this->venueImages]);
    }
    public function removeImage($imageIndex)
    {
        try {
            if (isset($this->venueImages[$imageIndex])) {
                $image = $this->venueImages[$imageIndex];
                unset($this->venueImages[$imageIndex]);
                $this->venueImages = array_values($this->venueImages);
                Log::info('Image removed:', ['imageIndex' => $imageIndex, 'venueImages' => $this->venueImages]);
                if ($image instanceof TemporaryUploadedFile) {
                    // handle temporary file if needed
                } elseif (isset($image['id'])) {
                    $this->deletedVenueImageIndices[] = $image['id'];
                }
            } else {
                throw new \Exception('Error: Invalid structure of venueImages array. Image ID not found at index: ' . $imageIndex);
            }
        } catch (\Exception $e) {
            Log::error('Error removing image:', ['error' => $e->getMessage()]);
        }
    }
    public function deleteVenueImage($imageId)
    {
        try {
            $image = VenueImage::find($imageId);
            if ($image) {
                $imagePath = public_path('images/venues/Venue_Image/' . $image->image);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
                $image->delete();
            }
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete image: ' . $e->getMessage());
        }
    }
    public function saveVenueImages($venue)
    {
        try {
            if (!$venue->id) {
                throw new \Exception('Venue ID tidak ditemukan');
            }
            $deletedImages = collect($this->venueImages)->filter(function ($image) {
                return is_numeric($image);
            })->all();
            foreach ($deletedImages as $deletedImage) {
                $this->deleteVenueImage($deletedImage);
            }
            foreach ($this->venueImages as $image) {
                if ($image instanceof UploadedFile && $image->isValid()) {
                    if (!in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                        throw new \Exception('Hanya file gambar yang diizinkan (JPG, JPEG, PNG, GIF)');
                    }
                    $venueName = $venue->name;
                    $originalFileName = $image->getClientOriginalName();
                    $randomString = Str::random(5);
                    $newVenueImageName = 'VENUE_' . date('YmdHis') . '_' . $venueName . '_' . $randomString . '.' . $originalFileName;
                    $storedPath = $image->storeAs('/Venue_Image', $newVenueImageName, 'public');
                    $venue->venueImages()->create([
                        'image' => $newVenueImageName,
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->addError('venue_images_upload', $e->getMessage());
            dd('Failed to save venue images: ' . $e->getMessage());
        }
    }
    public function updateVenue($venue)
    {
        $this->resetErrorBag();
        try {
            if (is_int($venue)) {
                $venue = Venue::findOrFail($venue);
            }
            DB::beginTransaction();
            $updatedData = [
                'name' => $this->name,
                'phone_number' => $this->phone_number,
                'information' => $this->information,
                'address' => $this->address,
                'village_id' => $this->village_id,
                'map_link' => $this->map_link,
            ];
            $imbChanged = $this->imb instanceof UploadedFile && $this->imb->isValid();
            $venue->update($updatedData);
            if ($venue->status === 2) {
                $venue->update(['status' => 0]);
            }
            if (!$imbChanged) {
                unset($updatedData['imb']);
                $venue->update($updatedData);
            } else {
                if ($venue->imb) {
                    $previousImbPath = 'IMB/' . $venue->imb;
                    if (Storage::disk('public')->exists($previousImbPath)) {
                        Storage::disk('public')->delete($previousImbPath);
                    }
                }
                $venueName = $this->name;
                $newImbName = 'IMB_' . uniqid() . '_' . $venueName . '.' . $this->imb->getClientOriginalExtension();
                $this->imb->storeAs('/IMB', $newImbName, 'public');
                $venue->update(['imb' => $newImbName]);
            }
            foreach ($this->deletedVenueImageIndices as $imageId) {
                $this->deleteVenueImage($imageId);
            }
            $this->saveOpeningHours($venue);
            $this->saveVenueData($venue);
            DB::commit();
            session()->flash('success', 'Data venue berhasil diperbarui bree.');
            return redirect()->route('owner.venue.show', ['venue' => $venue->id]);
        } catch (\Exception $e) {
            $this->addError('venue_id', $e->getMessage());
            DB::rollBack();
            dd('Failed to update Venue: ' . $e->getMessage());
        }
    }
    public function storeVenue()
    {
        $this->resetErrorBag();
        if ($this->currentStep == 5) {
            $this->validateVenueImages();
        }
        if (!session()->has('imb_path')) {
            $this->addError('imb', 'File IMB Belum di tambahkan.');
            return;
        }
        $newImbName = session('imb_path');
        try {
            $user = Auth::user();
            if (!$user || $user->role !== 'owner') {
                $this->addError('user', 'User is not authorized or not an owner.');
                return;
            }
            $venue = Venue::create([
                'owner_id' => $user->owner->id,
                'name' => $this->name,
                'phone_number' => $this->phone_number,
                'information' => $this->information,
                'address' => $this->address,
                'village_id' => $this->village_id,
                'map_link' => $this->map_link,
                'reject_note' => '-',
                'imb' => $newImbName,
            ]);
            if (!$venue) {
                throw new \Exception('Failed to create Venue.');
            }
            $this->saveVenueData($venue);
            session()->flash('success', 'Data venue berhasil ditambahkan bree.');
            return redirect()->route('owner.venue.index');
        } catch (\Exception $e) {
            $this->addError('venue_id', $e->getMessage());
        }
    }
}
