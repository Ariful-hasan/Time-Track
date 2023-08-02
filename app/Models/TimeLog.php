<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public static function calculateTotalByDate(string $date, string $projectId): Collection
    {
        $result = self::whereDate('created_at', $date)
        ->whereProjectId($projectId)
        ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, start_time, end_time)) as total')
        ->first();

        return collect($result);
    }
       
}
