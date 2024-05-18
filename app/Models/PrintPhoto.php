<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintPhoto extends Model
{
    use HasFactory;
    protected $fillable = [
        'size',
    ];

    public function printServiceEvents()
    {
        return $this->hasMany(PrintServiceEvent::class, 'print_photo_id', 'id');
    }
}
