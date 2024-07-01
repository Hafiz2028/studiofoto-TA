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
        'no_hp',
        'service_package_detail_id',
        'date',
        'payment_status',
        'rent_status',
        'book_type',
        'dp_price',
        'dp_price_date',
        'dp_payment',
        'total_price',
        'reject_note',
    ];

    public function servicePackageDetail()
    {
        return $this->belongsTo(ServicePackageDetail::class, 'service_package_detail_id', 'id');
    }

    public function rentDetails()
    {
        return $this->hasMany(RentDetail::class, 'rent_id', 'id');
    }
    public function rentPayments()
    {
        return $this->hasMany(RentPayment::class, 'rent_id', 'id');
    }
    public function rentCustomer()
    {
        return $this->hasOne(RentCustomer::class, 'rent_id', 'id');
    }
}
