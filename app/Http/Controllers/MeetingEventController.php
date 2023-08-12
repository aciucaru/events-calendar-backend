<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MeetingAppointment;
use App\Models\MeetingEvent;
use Illuminate\Http\Request;

class MeetingEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $meetingEvents = MeetingEvent::with('hostUser',
        //                                     'location',
        //                                     'meetingAppointments',
        //                                     'projects')
        //                                 ->get();

        $meetingEvents = MeetingEvent::all();

        return response()->json($meetingEvents, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedMeetingEvent = $request->validate(
            [
                'host_user_id_fk' => ['required', 'integer', 'numeric', 'min:1'],
                'location_id_fk' => ['required', 'integer', 'numeric', 'min:1'],
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:4096']
            ]
        );

        $meetingEvent = MeetingEvent::create($validatedMeetingEvent);
        // $meetingEvent->load('hostUser',
        //                     'location',
        //                     'meetingAppointments',
        //                     'projects'
        //                 );

        return response()->json($meetingEvent, 200); // 201 - succesfully created resource
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meetingEvent = MeetingEvent::find($id);

        if(!$meetingEvent)
            return response()->json('Meeting not found', 404); // 404 - resource not found
        else
            return response()->json($meetingEvent, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $meetingEvent = MeetingEvent::find($id);

        if(!$meetingEvent)
            return response()->json('Meeting not found', 404); // 404 - resource not found
        else
        {
            $validatedMeetingEvent = $request->validate(
                [
                    'host_user_id_fk' => ['exclude'], // the host of the meeting should not be changed
                    'location_id_fk' => ['integer', 'numeric', 'min:1'],
                    'title' => ['string', 'max:255'],
                    'description' => ['string', 'max:4096']
                ]
            );

            $meetingEvent->update($validatedMeetingEvent);

            return response()->json($meetingEvent, 200); // 200 - succesfull request, resource updated and transmitted
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meetingEvent = MeetingEvent::find($id);

        if(!$meetingEvent)
            return response()->json('Meeting not found', 404); // 404 - resource not found
        else
        {
            $meetingEvent->delete();
            return response()->json('Meeting deleted', 200); // 200 - succesfull request
        }
    }

    /* Since a MeetingEvent does not contain any date information, the date information is stored
    in a MeetingAppointment object. Because of this you cannot have a MeetingEvent without an
    associated MeetingApoointment.
    This method creates both the MeetingEvent and an associated MeetingAppointment and receives the
    following JSON object:
    {
        // data for MeetingEvent object:
        'host_user_id_fk',
        'location_id_fk',
        'title',
        'description',

        // data for MeetingAppointment object
        'start',
        'end'
    }
     */
    public function storeWithAppointment(Request $request)
    {
        $validatedRequestData = $request->validate(
            [
                // data for MeetingEvent object:
                'host_user_id_fk' => ['required', 'integer', 'numeric', 'min:1'],
                'location_id_fk' => ['required', 'integer', 'numeric', 'min:1'],
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:4096'],

                // data for MeetingAppointment object
                'start' => ['required', 'date'],
                'end' => ['required', 'date']
            ]
        );

        // save the MeetingEvent in the database and get the stored object (including it's id)
        $meetingEvent = MeetingEvent::create($validatedRequestData);

        // add required data for MeetingAppointment
        $validatedRequestData['meeting_id_fk'] = $meetingEvent['id'];
        $validatedRequestData['active'] = 1; // this is the new active appointment corresponding to the meeting
        //  save the MeetingAppointment to database
        $meetingAppointment = MeetingAppointment::create($validatedRequestData);

        return response()->json($meetingEvent, 200); // 201 - succesfully created resource
    }

    /* This method updates the appointment of a meeting, by creating a new MeetingAppointment object
    in the database and assigning it to the specified meeting. The new appointment becomes the active one,
    and the last appointment of the meeting becomes inactive, obsolete ('active' => 0).
    The JSON object corresponding to the new MeetingAppointment looks like this:
    {
        "id": 20, // ignored, unnecessary
        "meeting_id_fk": 1, // ignored
        "active": 1, // ignored
        "start": "2023-08-07 12:45:00",
        "end": "2023-08-07 13:45:00"
    } */
    public function updateAppointment(Request $request, string $meetingId)
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
                    'id' => ['exclude'], // unnecessary
                    'meeting_id_fk' => ['exclude'], // the id of the meeting is already in the request parameter
                    'active' => ['exclude'], // the active status will always be 1 for new appointments
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
