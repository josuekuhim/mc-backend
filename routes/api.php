<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SpecialityController;
use App\Http\Controllers\Api\ClinicianController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rotas da API sem autenticação para testes dos CRUDs
| Observação: Clinician substitui User neste aplicativo
|
*/

// Rota de teste para verificar se a API está funcionando
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando corretamente!']);
});

// Rotas para Especialidades
Route::apiResource('specialities', SpecialityController::class);

// Rotas para Profissionais (que também são os usuários do sistema)
Route::apiResource('clinicians', ClinicianController::class);
Route::get('clinicians/{clinician}/patients', [ClinicianController::class, 'patients']);
Route::get('clinicians/{clinician}/appointments', [ClinicianController::class, 'appointments']);

// Rotas para Pacientes
Route::apiResource('patients', PatientController::class);
Route::get('patients/{patient}/appointments', [PatientController::class, 'appointments']);
Route::get('patients/{patient}/documents', [PatientController::class, 'documents']);

// Rotas para Consultas
Route::apiResource('appointments', AppointmentController::class);
Route::get('appointments/calendar', [AppointmentController::class, 'calendar']);
Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);

// Rotas para Notas de Sessão
Route::apiResource('appointments.notes', NoteController::class);

// Rotas para Documentos
Route::apiResource('documents', DocumentController::class);
Route::get('documents/{document}/download', [DocumentController::class, 'download']);

// Rotas para o Dashboard
Route::prefix('dashboard')->group(function () {
    Route::get('stats', [DashboardController::class, 'stats']);
    Route::get('appointments/recent', [DashboardController::class, 'recentAppointments']);
    Route::get('appointments/chart', [DashboardController::class, 'appointmentsChart']);
    Route::get('patients/chart', [DashboardController::class, 'patientsChart']);
});