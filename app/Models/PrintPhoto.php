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

    public function printPhotoDetails()
    {
        return $this->hasMany(PrintPhotoDetail::class, 'print_photo_id', 'id');
    }
    public function framePhotoDetails()
    {
        return $this->hasMany(FramePhotoDetail::class, 'print_photo_id', 'id');
    }
}
