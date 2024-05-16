<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePackageDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'sum_person',
        'price',
        'dp_status',
        'dp_percentage',
        'service_package_id',
    ];

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class, 'service_package_id', 'id');
    }
}
