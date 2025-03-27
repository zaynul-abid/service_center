<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'id';

    protected $fillable = [
        'company_name',
        'contact_number',
        'address',
        'registration_number',
        'plan',
        'subscription_start_date',
        'subscription_end_date',
        'reserve_1',
        'reserve_2',
        'reserve_3',
        'reserve_4',
        'reserve_5',
    ];
}
