<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectPostRequest;
use App\Services\Projects\ProjectService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    function __construct(public ProjectService $service)
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $projects = $this->service->index();
        
        return view('project.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectPostRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
