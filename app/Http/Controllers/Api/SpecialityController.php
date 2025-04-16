<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialityRequest;
use App\Http\Resources\SpecialityResource;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $specialities = Speciality::query()
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return SpecialityResource::collection($specialities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SpecialityRequest $request): SpecialityResource
    {
        $speciality = Speciality::create($request->validated());

        return new SpecialityResource($speciality);
    }

    /**
     * Display the specified resource.
     */
    public function show(Speciality $speciality): SpecialityResource
    {
        return new SpecialityResource($speciality);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SpecialityRequest $request, Speciality $speciality): SpecialityResource
    {
        $speciality->update($request->validated());

        return new SpecialityResource($speciality);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Speciality $speciality): Response
    {
        $speciality->delete();

        return response()->noContent();
    }
}