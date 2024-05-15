<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'faktur',
        'service_package_detail_id',
        'customer_id',
        'opening_hour_id',
        'date',
        'payment_status',
        'dp_price',
        'total_price',
        'reject_note',
    ];

    public function servicePackageDetail()
    {
        return $this->belongsTo(ServicePackageDetail::class, 'service_package_detail_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function openingHour()
    {
        return $this->belongsTo(OpeningHour::class, 'opening_hour_id', 'id');
    }

    public function rentPayments()
    {
        return $this->hasMany(RentPayment::class, 'rent_id', 'id');
    }
}
