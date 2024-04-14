<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningHour extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'venue_id',
        'day_id',
        'hour_id',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'id');
    }

    public function day()
    {
        return $this->belongsTo(Day::class, 'day_id', 'id');
    }

    public function hour()
    {
        return $this->belongsTo(Hour::class, 'hour_id', 'id');
    }

    // public function openingHourDetails()
    // {
    //     return $this->hasMany(OpeningHourDetail::class, 'opening_hour_id', 'id');
    // }
}
