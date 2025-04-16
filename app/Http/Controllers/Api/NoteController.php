<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Appointment;
use App\Models\Note;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteRequest $request, Appointment $appointment): NoteResource
    {
        $note = $appointment->sessionNotes()->create($request->validated());

        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment, Note $note): NoteResource
    {
        if ($note->appointment_id !== $appointment->id) {
            abort(404);
        }

        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NoteRequest $request, Appointment $appointment, Note $note): NoteResource
    {
        if ($note->appointment_id !== $appointment->id) {
            abort(404);
        }

        $note->update($request->validated());

        return new NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment, Note $note): Response
    {
        if ($note->appointment_id !== $appointment->id) {
            abort(404);
        }

        $note->delete();

        return response()->noContent();
    }
}