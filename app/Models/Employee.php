<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'department_id',
        'name',
        'email',
        'phone',
        'address',
        'position',
        'service_status',
        'remarks',
        'password',
        'assigned_service_status',
        'user_id'
    ];

    protected $dates = ['created_at'];
    protected $hidden = ['password'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedServices()
    {
        return $this->hasMany(Service::class, 'employee_id');
    }
}
