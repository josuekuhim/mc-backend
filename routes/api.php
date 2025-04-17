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

Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando corretamente!']);
});

Route::apiResource('specialities', SpecialityController::class);

Route::apiResource('clinicians', ClinicianController::class);
Route::get('clinicians/{clinician}/patients', [ClinicianController::class, 'patients']);
Route::get('clinicians/{clinician}/appointments', [ClinicianController::class, 'appointments']);

Route::apiResource('patients', PatientController::class);
Route::get('patients/{patient}/appointments', [PatientController::class, 'appointments']);
Route::get('patients/{patient}/documents', [PatientController::class, 'documents']);

Route::apiResource('appointments', AppointmentController::class);
Route::get('appointments/calendar', [AppointmentController::class, 'calendar']);
Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);

Route::apiResource('appointments.notes', NoteController::class);

Route::apiResource('documents', DocumentController::class);
Route::get('documents/{document}/download', [DocumentController::class, 'download']);

Route::prefix('dashboard')->group(function () {
    Route::get('stats', [DashboardController::class, 'stats']);
    Route::get('appointments/recent', [DashboardController::class, 'recentAppointments']);
    Route::get('appointments/chart', [DashboardController::class, 'appointmentsChart']);
    Route::get('patients/chart', [DashboardController::class, 'patientsChart']);
});

Route::post('/login', 'Auth\LoginController@apiLogin');
Route::post('/logout', 'Auth\LoginController@apiLogout')->middleware('auth:sanctum');
Route::get('/user', 'Auth\UserController@currentUser')->middleware('auth:sanctum');