<?php

namespace App\Traits;

use Carbon\Carbon;

trait TimelogTrait
{
    public function setDateTime(string $time, Carbon $carbon)
    {
        $dateTime = $carbon->toDateString() . " ". $time;

        return Carbon::parse($dateTime)->toDateTimeString();
    }
}