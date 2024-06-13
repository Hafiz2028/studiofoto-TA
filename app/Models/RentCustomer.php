<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'rent_id',
        'customer_id',
    ];

    public function rent()
    {
        return $this->belongsTo(Rent::class, 'rent_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
