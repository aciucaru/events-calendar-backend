<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();

        return response()->json($projects, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedProject = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:4096']
            ]
        );

        $project = Project::create($validatedProject);

        return response()->json($project, 201); // 201 - succesfully created resource
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::find($id);

        if(!$project)
            return response()->json('Project nout found', 404); // 404 - resource not found
        else
            return response()->json($project, 200); // 200 - succesfull request, resource transmitted
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::find($id);

        if(!$project)
            return response()->json('Project not found', 404); // 404 - resource not found
        else
        {
            $validatedProject = $request->validate(
                [
                    'name' => ['string', 'max:255'],
                    'description' => ['string', 'max:4096']
                ]
            );

            $project->update($validatedProject);

            return response()->json($project, 200); // 200 - succesfull request, resource updated and transmitted
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::find($id);

        if(!$project)
            return response()->json('Project not found', 404); // 404 - resource not found
        else
        {
            $project->delete();
            return response()->json('Project deleted', 200); // 200 - request succsesfull
        }
    }
}
