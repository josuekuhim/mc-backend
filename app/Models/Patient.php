<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        "clinician_id",
        "first_name",
        "last_name",
        "email",
        "phone",
        "birth_date",
        "gender",
        "marital_status",
        "address",
        "emergency_contact",
        "notes",
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function clinician()
    {
        return $this->belongsTo(Clinician::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
