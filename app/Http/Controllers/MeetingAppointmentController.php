<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MeetingAppointment;

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

    /* Adds an invitation sent as JSON request object to the appointment with specified id.
    The JSON object that is passed is a typical Invitation object:
    {
        "id": 1,
        "appointment_id_fk": 2,
        "guest_user_id_fk": 20,
        "guest_answer": "NO"
    } */
    public function addInvitation(Request $request, string $id)
    {
        $meetingAppointment = MeetingAppointment::find($id);

        if(!$meetingAppointment)
            return response()->json('Meeting appointment not found', 404); // 404 - resource not found
        else
        {
            $validatedInvitation = $request->validate(
                [
                    'id' => [ 'exclude' ], // ignored
                    'appointment_id_fk' => [ 'exclude' ], // ignored, the appointment id is passed as paramenter
                    'guest_user_id_fk' => [ 'integer', 'numeric', 'min:1' ],
                    'guest_answer' => [ Rule::in(['NO_ANSWER', 'YES', 'NO', 'MAYBE']) ]
                ]
            );

            $validatedInvitation['appointment_id_fk'] = $id;
            $newInvitation = Invitation::create($validatedInvitation);

            return response()->json($newInvitation, 200);
        }
    }
}
