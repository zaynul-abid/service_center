<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'usertype',
        'company_id',
    ];

    // Enable soft deletes

    protected $dates = ['deleted_at']; // Define soft delete column


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isFounder(): bool
    {
        return $this->usertype === 'founder';
    }

    public function isSuperAdmin(): bool
    {
        return $this->usertype === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return $this->usertype === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->usertype === 'employee';
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
