<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, SoftDeletes;


    protected $dates = [
        'subscription_start_date',
        'subscription_end_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'subscription_start_date' => 'datetime',
        'subscription_end_date' => 'datetime',
    ];
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_key',
        'company_name',
        'contact_number',
        'address',
        'registration_number',
        'plan_id',
        'plan_description',
        'final_price',
        'plan_amount',
        'amount',
        'status',
        'discount',
        'subscription_start_date',
        'subscription_end_date',
        'original_company_id',
        'is_renewed' ,
        'reserve_1',
        'reserve_2',
        'reserve_3',
        'reserve_4',
        'reserve_5',
    ];


    public static function generateCompanyKey()
    {
        return 'COMP-' . strtoupper(Str::random(8));  // Generates a key like COMP-1A2B3C4D
    }


    public function getStatusAttribute($value)
    {
        return Carbon::now()->gt(Carbon::parse($this->subscription_end_date)) ? 'expired' : $value;
    }



    protected static function booted()
    {
        static::saving(function ($company) {
            if (Carbon::now()->gt(Carbon::parse($company->subscription_end_date))) {
                $company->status = 'expired';
            }
        });
    }

    // Relationship to original company
    public function originalCompany()
    {
        return $this->belongsTo(Company::class, 'original_company_id');
    }

// Relationship to renewals
    public function renewals()
    {
        return $this->hasMany(Company::class, 'original_company_id');
    }

    public function canBeRenewed()
    {
        return $this->status === 'expired' && !$this->is_renewed;
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
