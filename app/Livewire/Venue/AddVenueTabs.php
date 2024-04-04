<?php

namespace App\Livewire\Venue;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethodDetail;
use Livewire\WithFileUploads;
use App\Models\Venue;
use App\Models\PaymentMethod;
use App\Models\VenueImage;
use App\Models\Day;
use App\Models\Hour;
use App\Models\OpeningHour;

class AddVenueTabs extends Component
{
    use WithFileUploads;
    public $name, $phone_number, $information, $imb, $address, $latitude, $longitude;
    public $picture, $venue_image;
    public $upload = [];

    // variable step 3
    public $selectedPaymentMethod = [];
    public $bank_accounts = [];
    public $payment_methods;

    // variable step 4
    public $selectedOpeningDay = [];
    public $opening_hours = [];
    public $days, $hours;

    public $errorBag = null;
    public $totalSteps = 5;
    public $currentStep = 1;

    public function render()
    {
        $validationErrors = [];
        if ($this->currentStep == 3) {
            $validationErrors = $this->getErrorBag()->get('bank_accounts.*');
        }
        if ($this->currentStep == 4) {
            $validationErrors = $this->getErrorBag()->get('opening_hours.*');
        }
        return view('livewire.venue.add-venue-tabs', [
            'validationErrors' => $validationErrors,
        ]);
    }

    public function mount()
    {
        $this->currentStep = 4;
        $owner = Auth::guard('owner')->user();
        if ($owner) {
            $this->payment_methods = PaymentMethod::all();
            $this->days = Day::all();
            $this->hours = Hour::all();
            $this->initializeSelectedPaymentMethods();
            $this->initializeSchedules();
            $this->bank_accounts = [];
            $this->opening_hours = [];
            $this->errorBag = null;
        }
    }

    protected function initializeSelectedPaymentMethods()
    {
        foreach ($this->payment_methods as $payment_method) {
            $this->selectedPaymentMethod[$payment_method->id] = false;
        }
    }
    public function toggleBankAccountInput($payment_method_id)
    {
        if (array_key_exists($payment_method_id, $this->selectedPaymentMethod)) {
            $this->selectedPaymentMethod[$payment_method_id] = !$this->selectedPaymentMethod[$payment_method_id];
        } else {
            Log::error("Undefined array key: {$payment_method_id}");
            session()->flash('error_message', 'Terjadi kesalahan dalam memproses payment method Anda.');
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
    public function saveBankAccountDetails($venueId)
    {
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
    public function findMyLocation()
    {
        $latitude = 49.2125578; // Default latitude
        $longitude = 16.62662018; // Default longitude
        $this->emit('updateMap', $latitude, $longitude);
    }
    public function increaseStep()
    {
        $this->resetErrorBag();
        if (!$this->validationData()) {
            return;
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


    //step 4 jadwal
    //inisialisasi false opening_hours
    protected function initializeSchedules()
    {
        foreach ($this->days as $day) {
            $this->selectedOpeningDay[$day->id] = false;
            foreach ($this->hours as $hour) {
                $this->opening_hours[$day->id][$hour->id] = false;
            }
        }
    }
    //ubah status check box jadwal hari
    public function toggleDaySchedule($dayId)
    {
        Log::info("toggleDaySchedule called for dayId: {$dayId}");
        if (isset($this->selectedOpeningDay[$dayId])) {
            $this->selectedOpeningDay[$dayId] = !$this->selectedOpeningDay[$dayId];
        } else {
            $this->selectedOpeningDay[$dayId] = true; // Atau false, tergantung pada kebutuhan Anda
        }
        Log::info("New value for dayId {$dayId}: " . ($this->selectedOpeningDay[$dayId] ? 'true' : 'false'));
        $this->render();
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
    }
    //mengatur value selectedOpeningDay
    public function selectedOpeningDay($value, $dayId)
    {
        if (isset($this->selectedOpeningDay[$dayId])) {
            $this->selectedOpeningDay[$dayId] = $value;
        } else {
            $this->selectedOpeningDay[$dayId] = false;
            // Reset bank account value when unchecking the checkbox
            $this->opening_hours[$dayId] = '';
        }
    }
    //mengatur value opening_hours
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
    public function saveOpeningHours($venueId)
    {
        try {
            // Ambil venue berdasarkan ID
            $venue = Venue::findOrFail($venueId);

            // Hapus semua jadwal buka yang sebelumnya tersimpan
            $venue->openingHours()->delete();

            // Simpan hanya hari-hari yang memiliki jam buka yang dipilih
            foreach ($this->days as $day) {
                $dayId = $day->id;
                $daySelected = false;

                foreach ($this->hours as $hour) {
                    $hourId = $hour->id;
                    $isChecked = isset($this->selectedOpeningHours[$dayId][$hourId]) && $this->selectedOpeningHours[$dayId][$hourId];

                    // Jika jam dipilih, tandai bahwa hari ini memiliki jam buka yang dipilih
                    if ($isChecked) {
                        $daySelected = true;
                        break;
                    }
                }

                // Jika hari memiliki jam buka yang dipilih, simpan informasi jadwal buka
                if ($daySelected) {
                    foreach ($this->hours as $hour) {
                        $hourId = $hour->id;
                        $isChecked = isset($this->selectedOpeningHours[$dayId][$hourId]) && $this->selectedOpeningHours[$dayId][$hourId];

                        // Tambahkan data jadwal buka ke dalam database
                        $venue->openingHours()->create([
                            'day_id' => $dayId,
                            'hour_id' => $hourId,
                            'status' => $isChecked ? 2 : 1, // status 2 menunjukkan aktif, status 1 menunjukkan tidak aktif
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Tangani kesalahan jika terjadi
            $this->addError('save_opening_hours', 'Gagal menyimpan jadwal buka: ' . $e->getMessage());
        }
    }
    public function updatedSelectedOpeningHours($dayId, $hourId, $value)
    {
        $this->opening_hours[$dayId][$hourId] = $value;

        if ($value) {
            $this->opening_hours[$dayId][$hourId] = true;
        } else {
            unset($this->opening_hours[$dayId][$hourId]);
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
                'imb' => 'required|mimes:pdf|max:5000',
            ];
            $messages = [
                'name.required' => 'Isi nama venue anda.',
                'name.min' => 'Nama Venue minimal 2 karakter.',
                'phone_number.required' => 'Nomor telepon owner harus diisi.',
                'phone_number.min' => 'Nomor telepon minimal harus 8 angka.',
                'phone_number.max' => 'Nomor telepon maksimal harus 15 angka.',
                'imb.required' => 'File IMB harus diunggah.',
                'imb.mimes' => 'File IMB harus berupa file PDF.',
                'imb.max' => 'Ukuran file IMB maksimal adalah 5 MB.',
            ];

            if ($this->imb) {
                $originalName = $this->imb->getClientOriginalName();
                $venueName = $this->name;
                $newImbName = 'IMB_' . $venueName . '_' . $originalName;
                $upload_imb = $this->imb->storeAs('images/venues/IMB', $newImbName);
                session(['imb_path' => $upload_imb]);
            }
        } elseif ($this->currentStep == 2) {
            $rules = [
                'address' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ];
            $messages = [
                'address.required' => 'Alamat Venue harus diisi.',
                'address.string' => 'Alamat venue harus berupa teks.',
                'address.max' => 'Alamat tidak boleh lebih dari 255 karakter.',
                'latitude.required' => 'Latitude harus diisi.',
                'latitude.numeric' => 'Latitude harus berupa angka.',
                'longitude.required' => 'Longitude harus diisi.',
                'longitude.numeric' => 'Longitude harus berupa angka.',
            ];
        } elseif ($this->currentStep == 3) {
            $rules = [];
            $messages = [];

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
            $rules = [];
            $messages = [];

            // Validasi untuk memastikan setidaknya satu checkbox dengan ID 'openingHours' bernilai true
            $hasAtLeastOneOpeningHourSelected = false;
            foreach ($this->opening_hours as $dayId => $hours) {
                foreach ($hours as $hourId => $isSelected) {
                    if ($isSelected) {
                        $hasAtLeastOneOpeningHourSelected = true;
                        break 2; // Keluar dari loop saat menemukan satu yang terpilih
                    }
                }
            }
            if (!$hasAtLeastOneOpeningHourSelected) {
                $rules['opening_hours.*.*'] = 'required';
                $messages['opening_hours.*.*.required'] = 'Pilih Satu Jadwal hari dan setidaknya satu jam operasional.';
            }
            // dd($rules, $messages);


        }
        // dd($rules, $messages);
        $this->validate($rules, $messages);
        return true;
    }

    public function storeVenue()
    {
        $this->resetErrorBag();
        if ($this->currentStep == 5) {
            $this->validate([
                'picture' => 'required|image|mimes:png,jpg,jpeg|max:5000',
                'venue_image' => 'nullable|image|mimes:png,jpg,jpeg|max:5000',
            ], [
                'picture.required' => 'Foto profil venue harus diunggah.',
                'picture.image' => 'Foto profil venue harus berupa file gambar.',
                'picture.mimes' => 'Format gambar yang diperbolehkan hanya PNG, JPG, atau JPEG.',
                'picture.max' => 'Ukuran file foto profil venue maksimal adalah 5 MB.',

                'venue_image.image' => 'Foto gedung atau layanan venue harus berupa file gambar.',
                'venue_image.mimes' => 'Format gambar yang diperbolehkan hanya PNG, JPG, atau JPEG.',
                'venue_image.max' => 'Ukuran file foto gedung atau layanan venue maksimal adalah 5 MB.',
            ]);
        }
        if (!session()->has('imb_path')) {
            $this->addError('imb', 'File IMB not fond.');
            return;
        }

        $imbName = session('imb_path');

        $pictureName = 'VENUE_IMG_' . $this->picture->getClientOriginalName();
        $uploadPicture = $this->picture->storeAs('images/venues/Venue_Image', $pictureName);
        $this->upload['picture'] = $uploadPicture;

        $venueImageName = null;
        if ($this->venue_image) {
            $venueImageName = 'STUDIO_IMG_' . $this->venue_image->getClientOriginalName();
            $uploadVenueImage = $this->venue_image->storeAs('images/venues/Studio_Image', $venueImageName);
            $this->upload['venue_image'] = $uploadVenueImage;
        }
        session()->forget('imb_path');
        $venueId = Auth::guard('owner')->user()->venues->first()->id;
        try {
            if (!$venueId) {
                throw new \Exception('Venue ID not found anjir');
            }
            $this->saveVenueData($venueId, $imbName, $pictureName, $venueImageName);
            return redirect()->route('back.pages.owner.venue-manage.index-venue')->with('success', 'Data venue berhasil ditambahkan.');
        } catch (\Exception $e) {
            $this->addError('venue_id', $e->getMessage());
        }
    }

    protected function saveVenueData($venueId, $imbName, $pictureName, $venueImageName)
    {
        try {
            if (!$venueId) {
                throw new \Exception('Venue ID ngga ketemu');
            }
            $venue = Venue::create([
                'owner_id' => Auth::guard('owner')->id(),
                'venue_id' => $venueId,
                'name' => $this->name,
                'phone_number' => $this->phone_number,
                'information' => $this->information,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'imb' => $imbName,
                'picture' => $pictureName,
            ]);
            if ($venue) {
                $this->saveOpeningHours($venueId);
                $this->saveBankAccountDetails($venueId);
                if ($venueImageName) {
                    VenueImage::create([
                        'venue_id' => $venue->id,
                        'image' => $venueImageName,
                    ]);
                }
            } else {
                throw new \Exception('Failed to create Venue.');
            }
        } catch (\Exception $e) {
            $this->addError('venue_creation', $e->getMessage());
        }
    }
}
