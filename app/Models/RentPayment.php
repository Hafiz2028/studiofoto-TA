<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPayment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'image',
        'rent_id',
        'payment_method_detail_id',
    ];

    public function rent()
    {
        return $this->belongsTo(Rent::class, 'rent_id', 'id');
    }

    public function paymentMethodDetail()
    {
        return $this->belongsTo(PaymentMethodDetail::class, 'payment_method_detail_id', 'id');
    }
}
