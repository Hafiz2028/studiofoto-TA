<?php

namespace App\Livewire\Venue;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\PaymentMethodDetail;
use Livewire\WithFileUploads;
use App\Models\Venue;
use App\Models\PaymentMethod;
use App\Models\Day;
use App\Models\Hour;
use App\Models\OpeningHour;

class AddVenueTabs extends Component
{
    use WithFileUploads;
    public $name, $phone_number, $information, $imb, $address, $latitude, $longitude;
    public $jadwal_hari = [], $jadwal_jam = [], $picture, $venue_image;

    public $selectedPaymentMethod = [];
    public $bank_accounts = [];
    public $payment_methods;
    public $days, $hours;
    public $selectedOpeningHours = [];
    public $savedJadwalJam;


    public $totalSteps = 5;
    public $currentStep = 1;

    public function render()
    {
        $this->days = Day::all();
        $this->hours = Hour::all();
        return view('livewire.venue.add-venue-tabs', [
            'days' => $this->days,
            'hours' => $this->hours,
        ]);
    }

    public function mount()
    {
        $this->currentStep = 1;
        $this->days = Day::all();

        foreach ($this->days as $day) {
            $this->jadwal_hari[$day->id] = false;
        }

        $user = Auth::user();
        if ($user) {
            $this->payment_methods = PaymentMethod::all();
            $this->initializeSelectedPaymentMethods();
        }
    }

    protected function initializeSelectedPaymentMethods()
    {
        foreach ($this->payment_methods as $payment_method) {
            $this->selectedPaymentMethod[$payment_method->id] = false;
        }
    }

    protected function initializeDays()
    {
        $days = Day::all();
        foreach ($days as $day) {
            $this->jadwal_hari[$day->id] = false;
        }
    }

    public function checkAll($dayId)
    {
        $allChecked = true;

        foreach ($this->jadwal_jam[$dayId] as $hourId => $value) {
            if (!$value) {
                $allChecked = false;
                break;
            }
        }

        // Jika belum semua dicentang, centang semua jam
        if (!$allChecked) {
            foreach ($this->jadwal_jam[$dayId] as $hourId => $value) {
                $this->jadwal_jam[$dayId][$hourId] = true;
            }
        } else {
            // Jika semua sudah dicentang, hapus ceklis semua jam
            foreach ($this->jadwal_jam[$dayId] as $hourId => $value) {
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
        // Iterasi semua jam dan ceklis jika belum dicentang, dan uncheck jika sudah tercentang
        foreach ($this->hours as $hour) {
            $hourId = $hour->id;
            if ($hourId >= 16 && $hourId <= 45) {
                if (!$this->jadwal_jam[$dayId][$hourId]) {
                    $this->jadwal_jam[$dayId][$hourId] = true;
                }
            } else {
                if ($this->jadwal_jam[$dayId][$hourId]) {
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

    public function toggleSchedule($dayId, $hourId)
    {
        $isChecked = $this->jadwal_jam[$dayId][$hourId];

        // Tandai jadwal yang dipilih oleh pengguna
        if ($isChecked) {
            $this->selectedOpeningHours[$dayId][$hourId] = true;
        } else {
            unset($this->selectedOpeningHours[$dayId][$hourId]);
        }
    }

    public function saveOpeningHours()
    {
        $user = Auth::user();
        if ($user) {
            foreach ($this->selectedOpeningHours as $dayId => $hours) {
                foreach ($hours as $hourId => $isChecked) {
                    OpeningHour::updateOrCreate([
                        'venue_id' => $user->venue_id,
                        'day_id' => $dayId,
                        'hour_id' => $hourId,
                    ]);
                }
            }
        }
    }

    public function increaseStep()
    {
        $this->currentStep++;
        if ($this->currentStep > $this->totalSteps) {
            $this->currentStep = $this->totalSteps;
        }
    }
    public function decreaseStep()
    {
        $this->currentStep--;
        if ($this->currentStep < 1) {
            $this->currentStep = 1;
        }
    }
    public function toggleBankAccountInput($payment_method_id)
    {
        $this->selectedPaymentMethod[$payment_method_id] = !$this->selectedPaymentMethod[$payment_method_id];
    }
    public function updatedSelectedPaymentMethod($value, $payment_method_id)
    {
        if ($value) {
            $this->selectedPaymentMethod[$payment_method_id] = true;
        } else {
            $this->selectedPaymentMethod[$payment_method_id] = false;
            // Reset bank account value when unchecking the checkbox
            $this->bank_accounts[$payment_method_id] = '';
        }
    }
    public function saveBankAccounts()
    {

        foreach ($this->selectedPaymentMethod as $payment_method_id => $isChecked) {
            if ($isChecked && isset($this->bank_accounts[$payment_method_id])) {
                PaymentMethodDetail::updateOrCreate(
                    ['payment_method_id' => $payment_method_id, 'venue_id' => Auth::guard('owner')->venue_id],
                    ['no_rek' => $this->bank_accounts[$payment_method_id]]
                );
            } else {
                PaymentMethodDetail::where('payment_method_id', $payment_method_id)
                    ->where('venue_id', Auth::guard('owner')->user()->venue_id)
                    ->delete();
            }
        }

        // Reset the input fields
        $this->bank_accounts = [];

        // Clear the checkboxes
        $this->selectedPaymentMethod = [];
    }

    public function updatedJadwalHari($value, $dayId)
    {
        if (!$value) {
            OpeningHour::where('venue_id', Auth::id())->where('day_id', $dayId)->delete();
        }
        $this->jadwal_hari[$dayId] = $value;
    }
    protected function updateDayStatus($value, $dayId)
    {
        if (!$value) {
            OpeningHour::where('venue_id', Auth::guard('owner')->user()->venue_id)
                ->where('day_id', $dayId)
                ->delete();
        } else {
            // If the day is selected, activate all associated hours
            $hours = Hour::pluck('id')->toArray();

            foreach ($hours as $hourId) {
                OpeningHour::updateOrCreate([
                    'venue_id' => Auth::guard('owner')->user()->venue_id,
                    'day_id' => $dayId,
                    'hour_id' => $hourId,
                ], [
                    'status' => 2,
                ]);
            }
        }

        // Update jadwal_hari based on current database records
        $this->jadwal_hari[$dayId] = $value;
    }
    protected function updateScheduleData($dayId)
    {
        $openingHours = OpeningHour::where('venue_id', Auth::guard('owner')->user()->venue_id)
            ->where('day_id', $dayId)
            ->pluck('hour_id')
            ->toArray();

        foreach ($this->hours as $hour) {
            $this->jadwal_jam[$dayId][$hour->id] = in_array($hour->id, $openingHours);
        }
    }
    public function toggleDaySchedule($dayId)
    {
        if ($this->jadwal_hari[$dayId]) {
            // Jika switch diaktifkan, perbarui jadwal jam berdasarkan status yang telah disimpan sebelumnya
            if (isset($this->savedJadwalJam[$dayId])) {
                $this->jadwal_jam[$dayId] = $this->savedJadwalJam[$dayId];
            } else {
                // Jika belum ada status jadwal jam yang disimpan sebelumnya, load jadwal jam baru
                $this->loadDaySchedule($dayId);
            }
        } else {
            // Jika switch dimatikan, simpan status jadwal jam sebelumnya
            $this->savedJadwalJam[$dayId] = $this->jadwal_jam[$dayId] ?? [];
            // Kemudian hilangkan list jadwal jam dan button
            unset($this->jadwal_jam[$dayId]);
        }
    }

    protected function loadDaySchedule($dayId)
    {
        $hours = Hour::all();
        foreach ($hours as $hour) {
            $this->jadwal_jam[$dayId][$hour->id] = false;
        }
    }





    public function storeVenue()
    {
    }
}
