<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use softDeletes;

    protected $primaryKey = 'id';

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'amount',
        'days', // or 'days' depending on your actual column name
        'status'
    ];


    public function companies()
    {
        return $this->hasMany(Company::class);
    }


}
