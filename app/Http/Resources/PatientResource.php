<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'clinician_id' => $this->clinician_id,
            'clinician' => new ClinicianResource($this->whenLoaded('clinician')),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'birth_date_formatted' => $this->when($this->birth_date, function () {
                return $this->birth_date->format('d/m/Y');
            }),
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'address' => $this->address,
            'emergency_contact' => $this->emergency_contact,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}