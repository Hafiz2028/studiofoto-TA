<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Rent;
use Carbon\Carbon;

class UpdateExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-expired-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired bookings and notify users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $expiredRents = Rent::where('rent_status', '!=', 4)
            ->whereHas('rentDetails.openingHour.hour', function ($query) use ($now) {
                $query->where('hour', '<', $now->format('H.i'));
            })->get();
        foreach ($expiredRents as $rent) {
            $rent->rent_status = 4;
            $rent->save();
            $this->notifyUser($rent);
        }
        $this->info('Expired bookings updated successfully.');
    }
    protected function notifyUser($rent)
    {
        // Notify the owner
        $ownerId = $rent->servicePackageDetail->servicePackage->serviceEvent->venue->owner_id;
        // $customerId = $rent->customer_id;
        $notificationData = [
            'name' => $rent->name,
            'date' => $rent->date,
            'time' => $rent->formatted_schedule
        ];

        // You can customize this part as per your notification system
        DB::table('notifications')->insert([
            'user_id' => $ownerId,
            'data' => json_encode($notificationData),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
