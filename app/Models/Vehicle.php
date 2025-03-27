<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_number',
        'vehicle_type',
        'vehicle_company',
        'vehicle_model',
        'fuel_type',
    ];

    public function setVehicleNumberAttribute($value)
    {
        $this->attributes['vehicle_number'] = strtoupper($value);
    }


    // Automatically save vehicle model in uppercase
    public function setVehicleModelAttribute($value)
    {
        $this->attributes['vehicle_model'] = strtoupper($value);
    }

    public function setVehicleCompanyAttribute($value)
    {
        $this->attributes['vehicle_company'] = strtoupper($value);
    }
}
