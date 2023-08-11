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
                    'host_user_id_fk' => ['integer', 'numeric', 'min:1'],
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

    public function getMeetingsByHost(string $hostUserId)
    {
        $meetingEvents = MeetingEvent::where('host_user_id_fk', $hostUserId)->get();

        return response()->json($meetingEvents, 200);
    }


    public function getMeetingsByHostAndDate(Request $request, string $hostUserId)
    {
        $requestData = $request->all();

        $meetingEvents = MeetingEvent::where('host_user_id_fk', $hostUserId)->get();

        return response()->json($meetingEvents, 200);
        // return response()->json($requestData , 200);
    }
}
