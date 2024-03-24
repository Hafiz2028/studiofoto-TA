<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOnPackageDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'venue_id',
        'service_package_id',
        'add_on_package_id',
        'sum',
        'total_price',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class);
    }

    public function addOnPackage()
    {
        return $this->belongsTo(AddOnPackage::class);
    }
}
