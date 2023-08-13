<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\User;

use App\Models\MeetingAppointment;
use App\Models\MeetingEvent;
use App\Models\OutOfOfficeEvent;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedUser = $request->validate(
            [
                'username' => ['required', 'ascii', 'max:255'],
                'name' => ['required', 'ascii', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'string']
            ]
        );

        // $validatedUser = $request->all();

        $user = User::create($validatedUser);

        return response()->json($user, 201); // 201 - succesfully created resource
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if(!$user)
            return response()->json('User not found', 404); // 404 - resource not found
        else
            return response()->json($user, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if(!$user)
            return response()->json('User not found', 404); // 404 - resource not found
        else
        {
            $validatedUser = $request->validate(
                [
                    'username' => ['ascii', 'max:255'],
                    'name' => ['ascii', 'max:255'],
                    'email' => ['email', 'max:255'],
                    'password' => ['string']
                ]
            );

            $user->update($validatedUser);

            return response()->json($user, 200); // 200 - succesfull request, resource updated and transmitted
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if(!$user)
            return response()->json('User not found', 404); // 404 - resource not found
        else
        {
            $user->delete();
            return response()->json('User deleted', 200); // 200 - request succsesfull
        }
    }

    // unnecessary, gets all meeting, uses to many resources
    public function getHostedMeetings(string $hostUserId)
    {
        $meetingEvents = MeetingEvent::where('host_user_id_fk', $hostUserId)->get();

        return response()->json($meetingEvents, 200);
    }

    public function getHostedMeetingsBydDate(Request $request, string $hostUserId)
    {
        $validatedRequestData = $request->validate(
            [
                'startDate' => [ 'required', 'date' ],
                'endDate' => [ 'required', 'date'],
            ]
        );

        $hostedMeetings = 
            MeetingEvent::where('host_user_id_fk', $hostUserId)
                        ->whereHas('meetingAppointments',
                                    function (Builder $query) use ($validatedRequestData)
                                    {
                                        $query->whereDate('start', '>=', $validatedRequestData['startDate'])
                                                ->whereDate('start', '<=', $validatedRequestData['endDate']);
                                    }
                                )
                        ->get();

        return response()->json($hostedMeetings, 200);
    }

    // Returns all active appointmnets hosted (created) bt a certain user
    public function getActiveHostedAppointments(string $hostUserId)
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

    /* Returns all active appointmnets hosted (created) bt a certain user, that start between 2 dates
    specified in the JSON request body.
    The JSON request body should look like this (example):
    {
        "startDate": "2023-08-07",
        "endDate": "2023-09-30"
    } */ 
    public function getActiveHostedAppointmentsByDate(Request $request, string $hostUserId)
    {
        $validatedRequestData = $request->validate(
            [
                'startDate' => [ 'required', 'date' ],
                'endDate' => [ 'required', 'date'],
            ]
        );

        $appointments = MeetingAppointment::whereHas('meetingEvent',
                                function (Builder $query) use($hostUserId)
                                {
                                    $query->where('host_user_id_fk', $hostUserId);
                                }
                            )
                            ->where('active', 1) // active == true
                            ->whereDate('start', '>=', $validatedRequestData['startDate'])
                            ->whereDate('start', '<=', $validatedRequestData['endDate'])
                            ->get();

        return response()->json($appointments, 200);
    }

    /* Returns all invitations to which an user was invited to and which start between 2 dates specified
    in the JSON request body.
    The JSON request body should look like this (example):
    {
        "startDate": "2023-08-07",
        "endDate": "2023-09-30"
    } */
    public function getActiveInvitationsByDate(Request $request, string $guestUserId)
    {
        $validatedRequestData = $request->validate(
            [
                'startDate' => [ 'required', 'date' ],
                'endDate' => [ 'required', 'date'],
            ]
        );

        $invitations = Invitation::whereHas('meetingAppointment',
                                        function (Builder $query) use($validatedRequestData)
                                        {
                                            $query->whereDate('start', '>=', $validatedRequestData['startDate'])
                                                    ->whereDate('start', '<=', $validatedRequestData['endDate'])
                                                    ->where('active', 1); // only invitation belongging to active appointment
                                        }
                                    )
                                    ->where('guest_user_id_fk', $guestUserId)
                                    ->get();

        return response()->json($invitations, 200); // 200 - succesfull request
    }

    /* Return out-of-office events which belong to a certain user and which start between 2 dates specified in
    the JSON request body.
    The JSON request body looks like this (example):
    {
        "startDate": "2023-08-07",
        "endDate": "2023-09-30"
    } */
    public function getOutOfOfficeEventsByDate(Request $request, string $userId)
    {
        $validatedRequestData = $request->validate(
            [
                'startDate' => [ 'required', 'date' ],
                'endDate' => [ 'required', 'date'],
            ]
        );

        // first, get all users corresponding to the ids in the '$userIdArray'
        $user = User::find($userId);

        $outOfOfficeEvents = OutOfOfficeEvent::whereBelongsTo($user)
                                                ->whereDate('start', '>=', $validatedRequestData['startDate'])
                                                ->whereDate('start', '<=', $validatedRequestData['endDate'])
                                                ->get();

        return response()->json($outOfOfficeEvents, 200);
    }
}
