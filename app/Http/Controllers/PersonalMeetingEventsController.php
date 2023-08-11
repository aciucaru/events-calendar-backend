<?php

namespace App\Http\Controllers;

use App\Models\MeetingEvent;
use App\Models\User;
use Illuminate\Http\Request;

/* Custom controller for working with the events that are related to a single user only.
Because this controller works with events related to a specific user, the user id is always passed
as an argument to the controller methods. */
class PersonalMeetingEventsController extends Controller
{
    // get all events associated with the user, from the current week only (Monday to Sunday)
    public function currentWeekMeetingEvents($id)
    {
        $user = User::find($id);

        if(!$user)
            return response()->json('User does not exist', 404);
        else
        {
            $events = $user->meetingAppointments()->where('active', 1)->get();

            return response()->json($events, 200);
        }

    }
}
