<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|in:report,evaluation,plan,referral,medical',
            'description' => 'nullable|string',
        ];

        // Only require file on creation
        if ($this->isMethod('post')) {
            $rules['file'] = 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'; // 10MB
        } else {
            $rules['file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240'; // 10MB
        }

        return $rules;
    }
}