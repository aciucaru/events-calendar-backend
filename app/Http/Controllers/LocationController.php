<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all();

        return response()->json($locations, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedLocation = $request->validate(
                [
                    'name' => ['required', 'string', 'max:255'],
                    'description' => ['required', 'string', 'max:4096']
                ]
            );

        $location = Location::create($validatedLocation);

        return response()->json($location, 201); // 201 - succesfully created resource
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $location = Location::find($id);

        if(!$location)
            return response()->json('Location resource not found', 404); // 404 - resource not found
        else
            return response($location, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $location = Location::find($id);

        if(!$location)
            return response()->json('Location resource not found', 404); // 404 - resource not found
        else
        {
            $validatedLocation = $request->validate(
                    [
                        'name' => ['string', 'max:255'],
                        'description' => ['string', 'max:255']
                    ]
                );

            $location->update($validatedLocation);

            return response()->json($location, 200); // 200 - succesfull request, resource updated and transmitted
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = Location::find($id);

        if(!$location)
            return response()->json('Location resource not found', 404); // 404 - resource not found
        else
        {
            $location->delete();
            return response()->json($location, 200); // 200 - succesfull request
        }
    }
}
