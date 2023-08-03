<?php

namespace App\Services\Projects;

use App\Models\Project;
use App\Services\AbstractServices;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectService extends AbstractServices
{    
    /**
     * pagination list of Project data
     *
     * @return LengthAwarePaginator or RedirectResponse
     */
    function index(): LengthAwarePaginator|RedirectResponse
    {
        try {
            return $projects = Project::paginate(10);
        } catch (\Throwable $th) {
            Log::error($th);

            return redirect()->back()->withErrors(['error' => 'Woops!, Something went wrong!']);
        }
    }
    
    /**
     * store new record of Project
     *
     * @param  array $data valid input
     * @return RedirectResponse
     */
    function store(array $data): RedirectResponse
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