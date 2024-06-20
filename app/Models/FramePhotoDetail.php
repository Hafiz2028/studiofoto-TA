<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FramePhotoDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'print_photo_id',
        'service_package_id',
    ];

    public function printPhoto()
    {
        return $this->belongsTo(PrintPhoto::class, 'print_photo_id', 'id');
    }

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class, 'service_package_id', 'id');
    }
}
