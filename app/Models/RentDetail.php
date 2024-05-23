<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'rent_id',
        'opening_hour_id',
    ];


    public function openingHour()
    {
        return $this->belongsTo(OpeningHour::class, 'opening_hour_id', 'id');
    }
    public function rent()
    {
        return $this->belongsTo(Rent::class, 'rent_id', 'id');
    }
}
