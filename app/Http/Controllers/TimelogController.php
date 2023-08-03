<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimelogPostRequest;
use App\Http\Requests\TimelogUpdateRequest;
use App\Services\Timelogs\TimelogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class TimelogController extends Controller
{
    function __construct(public TimelogService $service)
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $timelogs = $this->service->index();
        
        return view('timelog.index', compact('timelogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $projects = $this->service->getAllProjects();

        return view('timelog.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TimelogPostRequest $request): RedirectResponse
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
    public function edit(string $id): View
    {
        $projects = $this->service->getAllProjects();
        $timelog = $this->service->getTimelogDetailsById($id);

        return view('timelog.edit', compact('timelog', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimelogUpdateRequest $request, string $id): RedirectResponse
    {
        return $this->service->update($request->validated(), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        return $this->service->destroy($id);
    }
}
