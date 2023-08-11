<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MeetingAppointment;
use App\Models\MeetingEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MeetingAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetingAppointments = MeetingAppointment::all();

        return response()->json($meetingAppointments, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedMeetingAppointment = $request->validate(
            [
                'meeting_id_fk' => ['required', 'integer', 'numeric', 'min:1'],
                'active' => ['required', 'boolean'],
                'start' => ['required', 'date'],
                'end' => ['required', 'date']
            ]
        );

        $meetingAppointment = MeetingAppointment::create($validatedMeetingAppointment);

        return response()->json($meetingAppointment, 201); // 201 - succesfully created resource
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meetingAppointment = MeetingAppointment::find($id);

        if(!$meetingAppointment)
            return response()->json('Meeting appointment not found', 404); // 404 - resource not found
        else
            return response()->json($meetingAppointment, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $meetingAppointment = MeetingAppointment::find($id);

        if(!$meetingAppointment)
            return response()->json('Meeting appointment not found', 404); // 404 - resource not found
        else
        {
            $validatedMeetingAppointment = $request->validate(
                [
                    'meeting_id_fk' => ['integer', 'numeric', 'min:1'],
                    'active' => ['boolean'],
                    'start' => ['date'],
                    'end' => ['date']
                ]
            );

            $meetingAppointment->update($validatedMeetingAppointment);

            return response()->json($meetingAppointment, 200); // 200 - succesfull request, resource updated and transmitted
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meetingAppointment = MeetingAppointment::find($id);

        if(!$meetingAppointment)
            return response()->json('Meeting appointment not found', 404); // 404 - resource not found
        else
        {
            $meetingAppointment->delete();
            return response()->json('Meeting appointment deleted', 200); // 200 - request succsesfull
        }
    }

    // Returns all active appointmnets hosted (created) bt a certain user
    public function getActiveAppointmentsByHost(string $hostUserId)
    {

        $appointments = MeetingAppointment::whereHas('meetingEvent',
                                function (Builder $query) use($hostUserId)
                                {
                                    $query->where('host_user_id_fk', $hostUserId);
                                }
                            )
                            ->where('active', 1) // active == true
                            ->get();

        foreach ($appointments as $appointment)
        {
            $appointment->meetingEvent;
        };

        return response()->json($appointments, 200);
    }

    /* Returns all active appointmnets hosted (created) bt a certain user in a certain
    year and month specified in the JSON request body.
    The JSON request body should look like this (example):
    {
        "year": 2023,
        "month": 10
    } */ 
    public function getActiveAppointmentsByHostAndDate(Request $request, string $hostUserId)
    {
        $appointments = MeetingAppointment::whereHas('meetingEvent',
                                function (Builder $query) use($hostUserId)
                                {
                                    $query->where('host_user_id_fk', $hostUserId);
                                }
                            )
                            ->where('active', 1) // active == true
                            ->whereYear('start', $request->input('year'))
                            ->whereMonth('start', $request->input('month'))
                            ->get();

        return response()->json($appointments, 200);
    }

    /* This method updates the appointment of a meeting, by creating a new MeetingAppointment object
    in the database and assigning it to the specified meeting. The new appointment becomes the active one,
    and the last appointment of the meeting becomes inactive, obsolete ('active' => 0).
    The JSON object corresponding to the new MeetingAppointment looks like this:
    {
        "meeting_id_fk": 1, // ignored
        "active": 1, // ignored
        "start": "2023-08-07 12:45:00",
        "end": "2023-08-07 13:45:00"
    } */
    public function updateAppointmentByMeeting(Request $request, string $meetingId)
    {
        $meetingEvent = MeetingEvent::find($meetingId);

        if(!$meetingEvent)
            return response()->json('Meeting not found', 404); // 404 - resource not found
        else
        {
            /* because there will be a new active appointment for the meeting, the last appointment for that
            meeting must be made inactive (obsolete) */
            $previousMeetingAppointment = MeetingAppointment::where('meeting_id_fk', $meetingId)
                                                            ->orderByDesc('created_at')
                                                            ->first();
            $previousMeetingAppointment['active'] = 0; // change the active status
            $previousMeetingAppointment->update(); // save change to database

            $validatedMeetingAppointment = $request->validate(
                [
                    // 'id' => ['exclude'], // unnecessary
                    // 'meeting_id_fk' => ['exclude'], // the id of the meeting is already in the request parameter
                    // 'active' => ['exclude'], // the active status will always be 1 for new appointments
                    'start' => ['date'],
                    'end' => ['date']
                ]
            );

            // point the new appointment to the same meeting
            $validatedMeetingAppointment['meeting_id_fk'] = $meetingId;
            $validatedMeetingAppointment['active'] = 1; // make the new appointment the current one
            $newMeetingAppointment = MeetingAppointment::create($validatedMeetingAppointment); // save the new appointment to database

            return response()->json($newMeetingAppointment, 200); // 200 - succesfull request, resource updated and transmitted
        }
    }
}
