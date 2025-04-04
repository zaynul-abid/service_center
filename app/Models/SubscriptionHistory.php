<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    protected $fillable = [
        // Company Info
        'company_id',
        'company_name',
        'contact_number',
        'address',
        'registration_number',
        'company_key',

        // Plan Info
        'plan_id',
        'plan_name',
        'plan_amount',
        'plan_duration_days',

        // Subscription Info
        'start_date',
        'end_date',
        'final_amount',
        'discount',
        'status',
        'is_renewal'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
