<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOnPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function AddOnPackageDetails(){
        return $this->hasMany(AddOnPackageDetail::class, 'add_on_package_id','id');
    }
}
