<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OutOfOfficeEvent;
use App\Models\User;

class OutOfOfficeEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outOfOfficeEvents = OutOfOfficeEvent::all();

        return response()->json($outOfOfficeEvents, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedOutOfOfficeEvent = $request->validate(
            [
                'user_id_fk' => ['required', 'integer', 'numeric', 'min:1'],
                'description' => ['required', 'string', 'max:4096'],
                'start' => ['required', 'date'],
                'end' => ['required', 'date']
            ]
        );

        $outOfOfficeEvent = OutOfOfficeEvent::create($validatedOutOfOfficeEvent);

        return response()->json($outOfOfficeEvent, 201); // 201 - succesfully created resource
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $outOfOfficeEvent = OutOfOfficeEvent::find($id);

        if(!$outOfOfficeEvent)
            return response()->json('Out of office event not found', 404); // 404 - resource not found
        else
            return response()->json($outOfOfficeEvent, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $outOfOfficeEvent = OutOfOfficeEvent::find($id);

        if(!$outOfOfficeEvent)
            return response()->json('Out of office event not found', 404); // 404 - resource not found
        else
        {
            $validatedOutOfOfficeEvent = $request->validate(
                [
                    'user_id_fk' => ['integer', 'numeric', 'min:1'],
                    'description' => ['string', 'max:4096'],
                    'start' => ['date'],
                    'end' => ['date']
                ]
            );

            $outOfOfficeEvent->update($validatedOutOfOfficeEvent);

            return response()->json($outOfOfficeEvent, 200); // 200 - succesfull request, resource updated and transmitted
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $outOfOfficeEvent = OutOfOfficeEvent::find($id);

        if(!$outOfOfficeEvent)
            return response()->json('Out of office event not found', 404); // 404 - resource not found
        else
        {
            $outOfOfficeEvent->delete();
            return response()->json('Out of office event deleted', 200); // 200 - request succsesfull
        }
    }

    /* Return all out-of-office events for each user of an array of users and having a 'start' date
    in a certain year and month.
    The JSON request body looks like this (example):
    {
        "userIdArray": [1, 10, 12, 20],
        "year": 2023, // the year of the 'start' date of the event
        "month": 10 // the month of the 'start' date of the event
    } */
    // public function getEventsByUsersAndDate(Request $request)
    // {
    //     $validatedRequestData = $request->validate(
    //         [
    //             'userIdArray' => ['required', 'array'],
    //             'userIdArray.*' => ['integer', 'numeric', 'min:1' ], // check if all array elements are integers
    //             'year' => [ 'required', 'integer', 'numeric', 'min:1900' ],
    //             'month' => [ 'required', 'integer', 'numeric', 'min:1' , 'max:12'], // month is between 1...12
    //         ]
    //     );

    //     // first, get all users corresponding to the ids in the '$userIdArray'
    //     $users = User::findMany($validatedRequestData['userIdArray'])
    //             ->outOfOfficeEvents()
    //             ->unique()

    //     $outOfOfficeEvents = OutOfOfficeEvent::whereBelongs()
    // }
}
