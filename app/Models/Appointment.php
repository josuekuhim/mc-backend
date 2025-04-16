<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'date',
        'time',
        'duration',
        'type',
        'notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    
    public function getFormattedDateTimeAttribute()
    {
        return $this->date->format('d/m/Y') . ' ' . $this->time;
    }
}
