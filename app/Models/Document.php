<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'patient_id',
        'type',
        'file_path',
        'file_size',
        'file_type',
        'description',
    ];

    /**
     * Get the patient that owns the document.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}