<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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
}
