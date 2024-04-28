<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintPhotoDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'venue_id',
        'service_package_id',
        'print_photo_id',
        'price',
        ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'id');
    }

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class, 'service_package_id', 'id');
    }

    public function printPhoto()
    {
        return $this->belongsTo(PrintPhoto::class, 'print_photo_id', 'id');
    }
}
