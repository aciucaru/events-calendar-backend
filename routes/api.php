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

Route::apiResource('/user', UserController::class);
Route::apiResource('/location', LocationController::class);
Route::apiResource('/outofoffice', OutOfOfficeEventController::class);
Route::apiResource('/meeting', MeetingEventController::class);
Route::apiResource('/meeting-appointment', MeetingAppointmentController::class);
Route::apiResource('/invitation', InvitationController::class);
Route::apiResource('/project', ProjectController::class);

// Route::get('/user-meetings/{user_id}', [MeetingEventController::class, 'meetingsHostedBy']);
Route::get('/meeting/host/{user_id}/all', [MeetingEventController::class, 'getMeetingsByHost']);
Route::get('/meeting/host/{user_id}/byDate', [MeetingEventController::class, 'getMeetingsByHostAndDate']);
Route::post('/meeting', [MeetingEventController::class, 'storeWithAppointment']);


Route::get('/meeting-appointment/host/{userId}/all',
            [MeetingAppointmentController::class, 'getActiveAppointmentsByHost']
        );
Route::get('/meeting-appointment/host/{userId}/byDate',
            [MeetingAppointmentController::class, 'getActiveAppointmentsByHostAndDate']
        );


Route::get('/invitation/meeting-appointment/{meetingAppointmentId}/all',
            [InvitationController::class, 'getInvitationsByMeetingAppointment']
        );
Route::get('/invitation/meeting-appointment/{meetingAppointmentId}/byDate',
            [InvitationController::class, 'getActiveInvitationsByGuestAndDate']
        );


        
        