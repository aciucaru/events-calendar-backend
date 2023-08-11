<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MeetingAppointment;
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

    public function getAppointmentsByHost(string $hostUserId)
    {

        $appointments = MeetingAppointment::whereHas('meetingEvent',
            function (Builder $query) use($hostUserId)
            {
                $query->where('host_user_id_fk', $hostUserId);
            }
        )->get();

        foreach ($appointments as $appointment)
        {
            $appointment->meetingEvent;
        };

        return response()->json($appointments, 200);
    }

    /* this method sends all meeting who's host ID is the $hostUserId and which belong to the 
    year and month specified in the JSON request body
    JSON request body should look like this:
    {
        "year": 2023,
        "month": 10
    } */ 
    public function getAppointmentsByHostAndDate(Request $request, string $hostUserId)
    {
        $appointments = MeetingAppointment::whereHas('meetingEvent',
                                function (Builder $query) use($hostUserId)
                                {
                                    $query->where('host_user_id_fk', $hostUserId);
                                }
                            )
                            ->whereYear('start', $request->input('year'))
                            ->whereMonth('start', $request->input('month'))
                            ->get();

        return response()->json($appointments, 200);
    }
}
