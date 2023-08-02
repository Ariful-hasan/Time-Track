<?php

namespace App\Services\Reports;

use App\Contracts\ReportContract;
use App\Models\Project;
use App\Models\TimeLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReportService implements ReportContract
{
    public function generateReport(array $data)
    {
        try {
            return match ($data['type']) {
                TimeLog::DAY_REPORT => $this->generateDayReport($data['project_id']),
                TimeLog::WEEK_REPORT => $this->generateWeekReport($data['project_id']),
                TimeLog::MONTH_REPORT => $this->generateMonthReport($data['project_id']),
                default => new NotFoundHttpException('Report Not Found.'),
            };
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Woops! something went wrong.']);
        }
    }

    public function getAllProjects()
    {
        try {
            return Project::select( Project::ID, Project::NAME)->get();
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Woops! something went wrong.']);
        }
    }

    function generateDayReport(string $projectId)
    {
        try {
            $carbon = Carbon::now();

            return TimeLog::calculateTotalByDate($carbon->toDateString(), $projectId);
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Woops! something went wrong.']);
        }
    }

    function generateWeekReport(string $projectId)
    {
        
    }

    function generateMonthReport(string $projectId)
    {
        
    }
}