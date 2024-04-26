<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOnPackageDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_package_id',
        'add_on_package_id',
        'sum',
    ];

    public function servicePackage()
    {
        return $this->belongsTo(ServicePackage::class);
    }

    public function addOnPackage()
    {
        return $this->belongsTo(AddOnPackage::class);
    }
}
