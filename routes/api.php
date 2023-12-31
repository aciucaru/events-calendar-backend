<?php

use App\Http\Controllers\InvitationController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MeetingAppointmentController;
use App\Http\Controllers\MeetingEventController;
use App\Http\Controllers\OutOfOfficeEventController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PersonalMeetingEventsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::apiResource('/user', UserController::class);
// Route::apiResource('/location', LocationController::class);
// Route::apiResource('/outofoffice', OutOfOfficeEventController::class);
// Route::apiResource('/meeting', MeetingEventController::class);
// Route::apiResource('/meeting-appointment', MeetingAppointmentController::class);
// Route::apiResource('/invitation', InvitationController::class);
// Route::apiResource('/project', ProjectController::class);

Route::get('/user/all', [UserController::class, 'index']); // unnecessary
Route::get('/user/{userId}/allHostedMeetings', [UserController::class, 'getHostedMeetings']); // unnecessary
Route::get('/user/{userId}/hostedMeetingsByDate', [UserController::class, 'getHostedMeetingsBydDate']);
Route::get('/user/{userId}/allActiveHostedAppointments', [UserController::class, 'getActiveHostedAppointments']);
Route::get('/user/{userId}/activeHostedAppointmentsByDate', [UserController::class, 'getActiveHostedAppointmentsByDate']);
Route::get('/user/{userId}/activeInvitationsByDate', [UserController::class, 'getActiveInvitationsByDate']);
Route::get('/user/{userId}/outOfOfficeEventsByDate', [UserController::class, 'getOutOfOfficeEventsByDate']);


Route::get('/meeting', [MeetingEventController::class, 'index']);
Route::get('/meeting/{meetingId}', [MeetingEventController::class, 'show']);
Route::post('/meeting', [MeetingEventController::class, 'storeWithAppointment']);
Route::put('/meeting/{meetingId}/appointment', [MeetingEventController::class, 'updateAppointment']);


Route::get('/meeting-appointment/{appointmentId}/invitation', [MeetingAppointmentController::class, 'getInvitations']);
Route::post('/meeting-appointment/{appointmentId}/invitation', [MeetingAppointmentController::class, 'addInvitation']);
Route::delete('/meeting-appointment/{appointmentId}/invitation', [MeetingAppointmentController::class, 'removeInvitations']);

Route::post('/invitation/{invitationId}/answerInvitation', [InvitationController::class, 'answerInvitation']);





        
        