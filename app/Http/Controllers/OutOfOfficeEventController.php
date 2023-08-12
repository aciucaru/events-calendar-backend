<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OutOfOfficeEvent;
use Illuminate\Http\Request;

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

    /* Return all out-of-office events for each user of an array of users and starting at a
    certain date.
    The JSON request body looks like this (example):
    {
        "userIdArray": [1, 10, 12, 20],
        "year": 2023, // start year
        "month": 10 // start month
    } */
    public function getEventsByUsersAndDate(Request $request)
    {
        $validatedRequestData = $request->validate(
            [
                'userIdArray' => ['required', 'integer', 'numeric', 'min:1'],
                'location_id_fk' => ['required', 'integer', 'numeric', 'min:1'],
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:4096'],
            ]
        );
    }
}
