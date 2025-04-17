<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Clinician extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'speciality_id',
        'license_number',
        'is_active',
        'availability_schedule',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'availability_schedule' => 'array',
        'password' => 'hashed',
    ];

    /**
     * Get the speciality that owns the clinician.
     */
    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }

    /**
     * Get the patients for the clinician.
     */
    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}