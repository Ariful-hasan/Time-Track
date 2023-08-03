<?php

namespace App\Traits;

use Carbon\Carbon;

trait TimelogTrait
{    
    /**
     * convert time to datetime Y-m-d H:i:s
     *
     * @param  string $time
     * @param  Carbon $carbon
     * @return string
     */
    public function setDateTime(string $time, Carbon $carbon): string
    {
        $dateTime = $carbon->toDateString() . " ". $time;

        return Carbon::parse($dateTime)->toDateTimeString();
    }
    
    /**
     * convert seconds to hour format H:i:s
     *
     * @param  int $seconds
     * @return string
     */
    function secondsToHourFormat(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds - ($hours * 3600)) / 60);
        $seconds = $seconds - ($hours * 3600) - ($minutes * 60);
    
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}