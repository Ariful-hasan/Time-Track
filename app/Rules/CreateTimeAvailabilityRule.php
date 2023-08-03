<?php

namespace App\Rules;

use App\Models\TimeLog;
use App\Traits\TimelogTrait;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CreateTimeAvailabilityRule implements ValidationRule
{
    use TimelogTrait;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $start_time = $this->setDateTime($value, Carbon::today());
        $end_time = $this->setDateTime(request()->get('end_time'), Carbon::today());
        
        $exist = TimeLog::where(function ($query) use ($start_time, $end_time) {
            $query->where(function ($subQuery) use ($start_time, $end_time) {
                $subQuery->where('start_time', '<=', $end_time)
                    ->where('end_time', '>=', $start_time);
            })
            ->orWhere(function ($subQuery) use ($start_time, $end_time) {
                $subQuery->where('start_time', '<=', $end_time)
                    ->where('end_time', '>=', $end_time);
            })
            ->orWhere(function ($subQuery) use ($start_time, $end_time) {
                $subQuery->where('start_time', '>=', $start_time)
                    ->where('end_time', '<=', $end_time);
            });
            })
            ->get();
        
        if (!$exist->isEmpty()) {
            $fail(__('Time exist.'));
        }
    }
}
