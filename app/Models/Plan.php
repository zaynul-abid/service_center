<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'amount',
        'days', // or 'days' depending on your actual column name
        'status'
    ];

    public function companies()
    {
        return $this->hasMany(Company::class, 'company_plan_id');
    }

}
