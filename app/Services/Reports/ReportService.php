<?php

namespace App\Services\Reports;

use App\Contracts\ReportContract;
use App\Models\TimeLog;
use App\Traits\TimelogTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReportService implements ReportContract
{
    use TimelogTrait;
    
    /**
     * this is the entry point for generating 
     * different type reports regarding condition
     * 
     *
     * @param  array $data report types
     * @return Collection report
     */
    public function generateReport(array $data): Collection 
    {
        try {
            return match ($data['type']) {
                TimeLog::DAY_REPORT => $this->generateDayReport(),
                TimeLog::WEEK_REPORT => $this->generateWeekReport(),
                TimeLog::MONTH_REPORT => $this->generateMonthReport(),
                default => new NotFoundHttpException('Report Not Found.'),
            };
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Woops! something went wrong.']);
        }
    }
    
    /**
     * generate day report by project wise
     *
     * @return Collection report
     */
    function generateDayReport(): Collection 
    {
        try {
            $carbon = Carbon::now();
            $result = TimeLog::calculateTotalTimeByProjectWise(endDate:$carbon->toDateString(), startDate: $carbon->toDateString());
            
            return $this->setSecondsToHour($result);
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Woops! something went wrong.']);
        }
    }

    /**
     * generate week report by project wise
     *
     * @return Collection report
     */
    function generateWeekReport(): Collection 
    {
        try {
            $carbon = Carbon::now();
            $result = TimeLog::calculateTotalTimeByProjectWise(endDate: $carbon->toDateString(), startDate: $carbon->subWeek()->toDateString());
            
            return $this->setSecondsToHour($result);
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Woops! something went wrong.']);
        }
    }

    /**
     * generate month report by project wise
     *
     * @return Collection report
     */
    function generateMonthReport(): ?Collection 
    {
        try {
            $carbon = Carbon::now();
            $result = TimeLog::calculateTotalTimeByProjectWise(endDate: $carbon->toDateString(), startDate: $carbon->subMonth()->toDateString());
            
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