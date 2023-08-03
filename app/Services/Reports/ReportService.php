<?php

namespace App\Services\Reports;

use App\Contracts\ReportContract;
use App\Models\TimeLog;
use App\Traits\TimelogTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\RedirectResponse;

class ReportService implements ReportContract
{
    use TimelogTrait;
    
    /**
     * this is the entry point for generating 
     * different type reports regarding types
     * 
     *
     * @param  array $data report types
     * @return Collection report
     */
    public function generateReport(array $data): Collection|RedirectResponse
    {
        try {
            $carbon = Carbon::now();
            $endDate = $carbon->toDateString();
            
            $startDate = match($data['type']) {
                TimeLog::DAY_REPORT => $carbon->toDateString(),
                TimeLog::WEEK_REPORT => $carbon->subWeek()->toDateString(),
                TimeLog::MONTH_REPORT => $carbon->subMonth()->toDateString(),
                default => new NotFoundHttpException('Report Not Found.'),
            };
            
            $result = TimeLog::calculateTotalTimeByProjectWise(endDate: $endDate, startDate: $startDate);
            
            return $this->setSecondsToHour($result);
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Woops! something went wrong.']);
        }
    }
    
    /**
     * convert the total seconds to hour format
     * H:i:s
     *
     * @return Collection report
     */
    protected function setSecondsToHour(Collection $collection): Collection
    {
        return $collection->map(function ($report) {
            $report->total = $this->secondsToHourFormat($report->total);

            return $report;
        });
    }
}