<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends JsonResource
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
            'title' => $this->title,
            'patient_id' => $this->patient_id,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'type' => $this->type,
            'file_path' => $this->file_path,
            'file_url' => $this->when($this->file_path, function () {
                return url(Storage::url($this->file_path));
            }),
            'file_size' => $this->file_size,
            'file_size_formatted' => $this->when($this->file_size, function () {
                return $this->file_size < 1000 ? "{$this->file_size} KB" : round($this->file_size / 1000, 1) . " MB";
            }),
            'file_type' => $this->file_type,
            'description' => $this->description,
            'download_url' => route('api.documents.download', $this->id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}