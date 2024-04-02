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
    public $jadwal_hari = [], $jadwal_jam = [], $picture, $venue_image;
    public $upload = [];

    public $selectedPaymentMethod = [];
    public $bank_accounts = [];
    public $payment_methods;
    public $days, $hours;
    public $selectedOpeningHours = [];
    public $savedJadwalJam = [];

    public $errorBag = null;
    public $totalSteps = 5;
    public $currentStep = 1;

    public function render()
    {
        $this->days = Day::all();
        $this->hours = Hour::all();

        $validationErrors = [];
        if ($this->currentStep == 3) {
            $validationErrors = $this->getErrorBag()->get('bank_accounts.*');
        }
        return view('livewire.venue.add-venue-tabs', [
            'days' => $this->days,
            'hours' => $this->hours,
            'validationErrors' => $validationErrors,
        ]);
    }

    public function mount()
    {
        $this->currentStep = 4;
        $this->days = Day::all();

        if (!$this->days->isEmpty()) {
            foreach ($this->days as $day) {
                $this->jadwal_hari[$day->id] = false;
            }
        }

        $owner = Auth::guard('owner')->user();
        if ($owner) {
            $this->payment_methods = PaymentMethod::all();
            $this->initializeSelectedPaymentMethods();
            $this->bank_accounts = [];
            $this->errorBag = null;
        }
    }

    public function findMyLocation()
    {
        $latitude = 49.2125578; // Default latitude
        $longitude = 16.62662018; // Default longitude
        $this->emit('updateMap', $latitude, $longitude);
    }
    public function toggleBankAccountInput($payment_method_id)
    {
        if (array_key_exists($payment_method_id, $this->selectedPaymentMethod)) {
            $this->selectedPaymentMethod[$payment_method_id] = !$this->selectedPaymentMethod[$payment_method_id];
        } else {
            Log::error("Undefined array key: {$payment_method_id}");
            session()->flash('error_message', 'Terjadi kesalahan dalam memproses permintaan Anda.');
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
    protected function initializeSelectedPaymentMethods()
    {
        foreach ($this->payment_methods as $payment_method) {
            $this->selectedPaymentMethod[$payment_method->id] = false;
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
    //step 4
    protected function initializeDays()
    {
        $days = Day::all();
        foreach ($days as $day) {
            $this->jadwal_hari[$day->id] = false;
        }
    }
    public function checkAll($dayId)
    {
        if (!isset($this->jadwal_jam[$dayId])) {
            $this->jadwal_jam[$dayId] = [];
        }
        $allChecked = true;

        // Memeriksa apakah setiap jam sudah dicentang
        foreach ($this->hours as $hour) {
            $hourId = $hour->id;
            if (!isset($this->jadwal_jam[$dayId][$hourId]) || !$this->jadwal_jam[$dayId][$hourId]) {
                $allChecked = false;
                break;
            }
        }

        // Jika belum semua dicentang, centang semua jam
        if (!$allChecked) {
            foreach ($this->hours as $hour) {
                $hourId = $hour->id;
                $this->jadwal_jam[$dayId][$hourId] = true;
            }
        } else {
            // Jika semua sudah dicentang, hapus ceklis semua jam
            foreach ($this->hours as $hour) {
                $hourId = $hour->id;
                $this->jadwal_jam[$dayId][$hourId] = false;
            }
        }
    }
    public function uncheckAll($dayId)
    {
        foreach ($this->jadwal_jam[$dayId] as $hourId => $value) {
            $this->jadwal_jam[$dayId][$hourId] = false;
        }
    }
    public function checkWorkingHours($dayId)
    {
        // Memastikan jadwal_jam untuk hari ini terinisialisasi
        if (!isset($this->jadwal_jam[$dayId])) {
            $this->jadwal_jam[$dayId] = [];
        }

        // Iterasi semua jam dan ceklis jika belum dicentang, dan uncheck jika sudah tercentang
        foreach ($this->hours as $hour) {
            $hourId = $hour->id;
            if ($hourId >= 16 && $hourId <= 45) {
                // Check jam jika belum dicentang
                if (!isset($this->jadwal_jam[$dayId][$hourId]) || !$this->jadwal_jam[$dayId][$hourId]) {
                    $this->jadwal_jam[$dayId][$hourId] = true;
                }
            } else {
                // Uncheck jam jika sudah dicentang
                if (isset($this->jadwal_jam[$dayId][$hourId])) {
                    $this->jadwal_jam[$dayId][$hourId] = false;
                }
            }
        }
    }
    public function copySchedule($currentDayId, $nextDayId)
    {
        // Pastikan ada jadwal untuk hari ini yang akan disalin
        if (isset($this->jadwal_jam[$currentDayId])) {
            // Salin jadwal jam dari hari ini ke hari berikutnya
            foreach ($this->jadwal_jam[$currentDayId] as $hourId => $isChecked) {
                $this->jadwal_jam[$nextDayId][$hourId] = $isChecked;
            }

            // Aktifkan jadwal untuk hari berikutnya
            $this->jadwal_hari[$nextDayId] = true;
        }
    }
    public function toggleDaySchedule($dayId)
    {
        if ($this->jadwal_hari[$dayId]) {
            // Jika checkbox diaktifkan, coba memuat jadwal yang disimpan sebelumnya
            if (isset($this->savedJadwalJam[$dayId])) {
                $this->jadwal_jam[$dayId] = $this->savedJadwalJam[$dayId];
            } else {
                // Jika tidak ada jadwal yang disimpan, muat jadwal default
                $this->loadDaySchedule($dayId);
                // Simpan jadwal default ke savedJadwalJam
                $this->savedJadwalJam[$dayId] = $this->jadwal_jam[$dayId];
            }
        } else {
            // Jika checkbox dinonaktifkan, reset semua jadwal jam untuk hari ini
            $this->resetDaySchedule($dayId);
            // Hapus jadwal yang disimpan untuk hari ini
            unset($this->savedJadwalJam[$dayId]);
        }
    }
    protected function resetDaySchedule($dayId)
    {
        // Set semua jadwal jam untuk hari ini menjadi false
        $this->jadwal_jam[$dayId] = array_fill_keys(array_keys($this->jadwal_jam[$dayId]), false);
    }
    protected function loadDaySchedule($dayId)
    {
        $hours = Hour::all();
        foreach ($hours as $hour) {
            $this->jadwal_jam[$dayId][$hour->id] = false;
        }
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
    public function toggleSchedule($dayId, $hourId)
    {
        $isChecked = $this->jadwal_jam[$dayId][$hourId] ?? false;

        // Toggle the checkbox state
        $this->jadwal_jam[$dayId][$hourId] = !$isChecked;

        // Tandai jadwal yang dipilih oleh pengguna
        if ($this->jadwal_jam[$dayId][$hourId]) {
            $this->selectedOpeningHours[$dayId][$hourId] = true;
        } else {
            unset($this->selectedOpeningHours[$dayId][$hourId]);
        }
    }
    public function updatedSelectedOpeningHours($dayId, $hourId, $value)
    {
        if ($value) {
            $this->jadwal_jam[$dayId][$hourId] = true;
        } else {
            unset($this->jadwal_jam[$dayId][$hourId]);
        }

        // Debugging line
        dd($this->jadwal_jam);

        // Tambahkan ini untuk menampilkan nilai savedJadwalJam saat ada perubahan

    }

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
            $isAnyDaySelected = false;
            $isAnyScheduleFilled = false;
            $daysWithoutSchedule = [];
            foreach ($this->jadwal_hari as $dayId => $isChecked) {
                if ($isChecked) {
                    $isAnyDaySelected = true;

                    // Periksa apakah semua jadwal jam untuk hari ini bernilai false
                    $isAnyHourSelected = false;
                    foreach ($this->savedJadwalJam[$dayId] as $hourId => $isHourChecked) {
                        if ($isHourChecked) {
                            $isAnyHourSelected = true;
                            break; // No need to check further hours for this day
                        }
                    }

                    if (!$isAnyHourSelected) {
                        // If no hours are selected, add validation rule
                        $rules["savedJadwalJam.{$dayId}.*"] = 'required';
                        $messages["savedJadwalJam.{$dayId}.*.required"] = 'Minimal Ceklis satu jam untuk Hari ' . Day::find($dayId)->name . ' yang telah dibuka.';
                        $daysWithoutSchedule[] = Day::find($dayId)->name;
                    } else {
                        $isAnyScheduleFilled = true;
                    }
                }
            }
            if (!$isAnyDaySelected) {
                $rules['savedJadwalJam'] = 'required';
                $messages['savedJadwalJam.required'] = 'Minimal Buka dan Isi 1 Jadwal Hari Venue ini.';
            }
            if (!empty($daysWithoutSchedule)) {
                $this->addError('savedJadwalJam', '<div class="warning">Jangan Tutup Hari ' . implode(', ', $daysWithoutSchedule) . ' dengan kondisi Jadwal Jam masih diceklis.</div>');
            }
            $this->validate($rules, $messages);

            if ($isAnyDaySelected && $isAnyScheduleFilled && $isAnyHourSelected) {
                $this->increaseStep();
                return;
            }
            // dd($rules, $messages);
        }
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
