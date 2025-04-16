<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'patient_id' => $this->patient_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'date' => $this->date,
            'date_formatted' => $this->date->format('d/m/Y'),
            'time' => $this->time,
            'formatted_date_time' => $this->formatted_date_time,
            'duration' => $this->duration,
            'type' => $this->type,
            'notes' => $this->notes,
            'status' => $this->status,
            'session_notes' => NoteResource::collection($this->whenLoaded('sessionNotes')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}