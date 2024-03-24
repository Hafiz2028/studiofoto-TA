<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
    ];

    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class, 'day_id', 'id');
    }


}
