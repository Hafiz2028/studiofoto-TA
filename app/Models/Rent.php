<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'faktur',
        'name',
        'service_package_detail_id',
        'date',
        'payment_status',
        'rent_status',
        'book_type',
        'dp_price',
        'total_price',
        'print_photo_detail_id',
        'reject_note',
    ];

    public function servicePackageDetail()
    {
        return $this->belongsTo(ServicePackageDetail::class, 'service_package_detail_id', 'id');
    }

    public function printPhotoDetail()
    {
        return $this->belongsTo(PrintPhotoDetail::class, 'print_photo_detail_id', 'id');
    }

    public function rentDetails()
    {
        return $this->hasMany(RentDetail::class, 'rent_id', 'id');
    }
}
