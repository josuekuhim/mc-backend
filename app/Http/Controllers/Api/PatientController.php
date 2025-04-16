<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $patients = Patient::query()
            ->with('clinician')
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->clinician_id, function ($query, $clinicianId) {
                return $query->where('clinician_id', $clinicianId);
            })
            ->orderBy('last_name')
            ->paginate($request->per_page ?? 15);

        return PatientResource::collection($patients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PatientRequest $request): PatientResource
    {
        $patient = Patient::create($request->validated());

        return new PatientResource($patient);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient): PatientResource
    {
        return new PatientResource($patient->load(['clinician']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PatientRequest $request, Patient $patient): PatientResource
    {
        $patient->update($request->validated());

        return new PatientResource($patient->load(['clinician']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient): Response
    {
        $patient->delete();

        return response()->noContent();
    }
}