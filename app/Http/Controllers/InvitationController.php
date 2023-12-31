<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Invitation;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invitations = Invitation::all();

        return response()->json($invitations, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedInvitation = $request->validate(
            [
                'appointment_id_fk' => [ 'required', 'integer', 'numeric', 'min:1' ],
                'guest_user_id_fk' => [ 'required', 'integer', 'numeric', 'min:1' ],
                'guest_answer' => [ 'required', Rule::in(['NO_ANSWER', 'YES', 'NO', 'MAYBE']) ]
            ]
        );

        $invitation = Invitation::create($validatedInvitation);

        return response()->json($invitation, 201); // 201 - succesfully created resource
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invitation = Invitation::find($id);

        if(!$invitation)
            return response()->json('Invitation not found', 404); // 404 - resource not found
        else
            return response()->json($invitation, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $invitation = Invitation::find($id);

        if(!$invitation)
            return response()->json('Invitation not found', 404); // 404 - resource not found
        else
        {
            $validatedInvitation = $request->validate(
                [
                    'appointment_id_fk' => [ 'integer', 'numeric', 'min:1' ],
                    'guest_user_id_fk' => [ 'integer', 'numeric', 'min:1' ],
                    'guest_answer' => [ Rule::in(['NO_ANSWER', 'YES', 'NO', 'MAYBE']) ]
                ]
            );

            $invitation->update($validatedInvitation);

            return response()->json($invitation, 200); // 200 - succesfull request, resource updated and transmitted
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $invitation = Invitation::find($id);

        if(!$invitation)
            return response()->json('Invitation not found', 404); // 404 - resource not found
        else
        {
            $invitation->delete();
            return response()->json('Invitation deleted', 200); // 200 - succesfull request
        }
    }

    /* Method that accepts a certain invitation coresponding to the 'invitationId'.
    The JSON request object looks like this (example):
    {
        "guest_answer": "YES" | "NO" | "MAYBE" // "NO_ANSWER" is not acceptable, it has to be a concrete answer
    } */
    public function answerInvitation(Request $request, string $invitationId)
    {
        $validatedRequestData = $request->validate(
            [
                'guest_answer' =>  [ 'required', Rule::in(['YES', 'NO', 'MAYBE']) ]
            ]
        );

        $invitation = Invitation::find($invitationId);
        $invitation->guest_answer = $validatedRequestData['guest_answer'];
        $invitation->update();

        return response()->json($invitation, 200); // 200 - succesfull request, resource updated and transmitted
    }
}
