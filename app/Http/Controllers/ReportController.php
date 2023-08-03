<?php

namespace App\Http\Controllers;

use App\Contracts\ReportContract;
use App\Http\Requests\ReportRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public readonly array $types;

    function __construct(protected ReportContract $service)
    {
        $this->types = config('report.types');
    }

    function index(): View
    {
        $types = $this->types;
        
        return view('reports.index', compact('types'));
    }

    function generateReport(ReportRequest $request): View
    {
        $types = $this->types;
        $reports = $this->service->generateReport($request->validated());
        
        return view('reports.index', compact('types', 'reports'));
    }
}
