<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'content',
    ];

    /**
     * Get the appointment that owns the note.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}