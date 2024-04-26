<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintPhotoDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_package_id',
        'print_photo_id',
        'price',
        ];

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class, 'service_package_id', 'id');
    }

    public function printPhoto()
    {
        return $this->belongsTo(PrintPhoto::class, 'print_photo_id', 'id');
    }
}
