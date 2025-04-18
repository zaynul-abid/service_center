<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class
Service extends Model
{
    use HasFactory, SoftDeletes;

        protected $dates = [
            'booking_date',
            'created_at',
            'updated_at',
            'expected_delivery_date'
        ];


    protected $fillable = [
        'booking_id',
        'reference_number',
        'booking_date',
        'booking_time',
        'vehicle_number',
        'vehicle_type',
        'vehicle_company',
        'vehicle_model',
        'fuel_type',
        'fuel_level',
        'km_driven',
        'customer_name',
        'place',
        'contact_number_1',
        'contact_number_2',
        'service_details',
        'customer_complaint',
        'remarks',
        'cost',
        'expected_delivery_date',
        'expected_delivery_time',
        'company_id',
        'photos',
        'employee_id',
        'service_status',
        'employee_remarks' ,
        'technician_notes',
        'status',
    ];

    protected $casts = [
        'photos' => 'array',
        'booking_date' => 'date',
        'expected_delivery_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
