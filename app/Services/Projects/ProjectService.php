<?php

namespace App\Services\Projects;

use App\Models\Project;
use App\Services\AbstractServices;
use Illuminate\Support\Facades\Log;

class ProjectService extends AbstractServices
{
    function index() 
    {
        try {
            return $projects = Project::simplePaginate(10);
        } catch (\Throwable $th) {
            Log::error($th);

            return redirect()->back()->withErrors(['error' => 'Woops!, Something went wrong!']);
        }
    }

    function store(array $data) 
    {
        try {
            Project::create(array_merge($data, ['user_id' => auth()->user()->id]));

            return redirect()->route('projects.index')->with('success', 'Successfully Added');;
        } catch (\Throwable $th) {
            Log::error($th);
            
            return redirect()->back()->withErrors(['error' => 'Failed to create.']);
        }
    }
}