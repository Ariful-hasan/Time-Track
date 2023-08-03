<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class TimeLog extends Model
{
    use HasFactory;

    public const DAY_REPORT = 'day';
    public const WEEK_REPORT = 'week';
    public const MONTH_REPORT = 'month';

    protected $fillable = [
        'user_id',
        'project_id',
        'start_time',
        'end_time',
        'description',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new UserScope);
    }

    /**
     * Get the user associated with the time log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project associated with the time log.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getFormattedStartTimeAttribute(): string
    {
        return Carbon::parse($this->attributes['start_time'])->format("H:i");
    }

    public function getFormattedEndTimeAttribute(): string
    {
        return Carbon::parse($this->attributes['end_time'])->format("H:i");
    }
    
    /**
     * raw query is one possible way
     * but here we try to use eloquent
     *
     * @param  strin $startDate
     * @param  string $endDate
     * @return Collection with project and total time for each project
     */
    public static function calculateTotalTimeByProjectWise(string $startDate, string $endDate): Collection
    {
        // without eloquent
        // another possible way
        
        // $result = DB::select("
        //     SELECT
        //         `project_id`,
        //         SUM(TIMESTAMPDIFF(SECOND, start_time, end_time)) AS total
        //     FROM
        //         `time_logs`
        //     WHERE
        //         DATE(created_at) BETWEEN ? AND ?
        //     AND `user_id` = ?
        //     GROUP BY
        //         `project_id`
        // ", [$startDate, $endDate, auth()->user()->id]);


        $result = TimeLog::with(['project'])
        ->select('project_id', DB::raw('SUM(TIMESTAMPDIFF(SECOND, start_time, end_time)) AS total'))
        ->whereDate('created_at', '>=', $startDate)
        ->whereDate('created_at', '<=', $endDate)
        ->groupBy('project_id')
        ->get();
        
        return $result;
    }
       
}
