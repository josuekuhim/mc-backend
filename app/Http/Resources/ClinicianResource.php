<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicianResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'country' => $this->country,
            'speciality_id' => $this->speciality_id,
            'speciality' => new SpecialityResource($this->whenLoaded('speciality')),
            'license_number' => $this->license_number,
            'is_active' => $this->is_active,
            'availability_schedule' => $this->availability_schedule,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}