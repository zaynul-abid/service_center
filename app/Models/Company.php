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

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    protected $fillable = [
        'company_key',
        'company_name',
        'contact_number',
        'address',
        'registration_number',
        'plan_id',
        'final_price',
        'plan_amount',
        'amount',
        'status',
        'discount',
        'subscription_start_date',
        'subscription_end_date',
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
}
