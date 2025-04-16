<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $appointments = Appointment::query()
            ->with(['patient'])
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('date', $date);
            })
            ->when($request->patient_id, function ($query, $patientId) {
                return $query->where('patient_id', $patientId);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->orderBy('date')
            ->orderBy('time')
            ->paginate($request->per_page ?? 15);

        return AppointmentResource::collection($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request): AppointmentResource
    {
        $appointment = Appointment::create($request->validated());

        return new AppointmentResource($appointment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment): AppointmentResource
    {
        return new AppointmentResource($appointment->load(['patient', 'sessionNotes']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AppointmentRequest $request, Appointment $appointment): AppointmentResource
    {
        $appointment->update($request->validated());

        return new AppointmentResource($appointment->load(['patient', 'sessionNotes']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment): Response
    {
        $appointment->delete();

        return response()->noContent();
    }

    /**
     * Get appointments for calendar view.
     */
    public function calendar(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $appointments = Appointment::query()
            ->with(['patient'])
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return AppointmentResource::collection($appointments);
    }
}