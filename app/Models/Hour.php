<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    use HasFactory;
    protected $fillable = [
        'hour'
    ];

    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class, 'hour_id', 'id');
    }
}
