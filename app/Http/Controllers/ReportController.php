<?php

namespace App\Http\Controllers;

use App\Contracts\ReportContract;
use App\Http\Requests\ReportRequest;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public readonly array $types;

    function __construct(protected ReportContract $service)
    {
        $this->types = config('report.types');
    }

    function index() 
    {
        $projects = $this->service->getAllProjects();
        $types = $this->types;
        
        return view('reports.index', compact('projects', 'types'));
    }

    function generateReport(ReportRequest $request) 
    {
        $projects = $this->service->getAllProjects();
        $types = $this->types;
        $reports = $this->service->generateReport($request->validated());

        return view('reports.index', compact('projects', 'types', 'reports'));
    }
}
