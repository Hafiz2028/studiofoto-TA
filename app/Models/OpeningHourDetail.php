<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningHourDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_event_id',
        'opening_hour_id',
        ];

    public function serviceEvent()
    {
        return $this->belongsTo(ServiceEvent::class, 'service_event_id', 'id');
    }

    public function openingHour()
    {
        return $this->belongsTo(OpeningHour::class, 'opening_hour_id', 'id');
    }

    // public function rentDetails()
    // {
    //     return $this->hasMany(RentDetail::class, 'opening_hour_detail_id', 'id');
    // }
}
