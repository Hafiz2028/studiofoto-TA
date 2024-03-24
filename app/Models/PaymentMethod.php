<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'icon',
    ];

    public function paymentMethodDetails()
    {
        return $this->hasMany(PaymentMethodDetail::class, 'payment_method_id', 'id');
    }
}
