<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClinicianRequest;
use App\Http\Resources\ClinicianResource;
use App\Models\Clinician;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ClinicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $clinicians = Clinician::query()
            ->with('speciality')
            ->when($request->search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->speciality_id, function ($query, $specialityId) {
                return $query->where('speciality_id', $specialityId);
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                return $query->where('is_active', $request->is_active);
            })
            ->orderBy('last_name')
            ->paginate($request->per_page ?? 15);

        return ClinicianResource::collection($clinicians);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClinicianRequest $request): ClinicianResource
    {
        $clinician = Clinician::create($request->validated());

        return new ClinicianResource($clinician);
    }

    /**
     * Display the specified resource.
     */
    public function show(Clinician $clinician): ClinicianResource
    {
        return new ClinicianResource($clinician->load('speciality'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClinicianRequest $request, Clinician $clinician): ClinicianResource
    {
        $clinician->update($request->validated());

        return new ClinicianResource($clinician->load('speciality'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinician $clinician): Response
    {
        $clinician->delete();

        return response()->noContent();
    }
}