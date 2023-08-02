<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id'
    ];

    public const NAME = 'name';
    public const ID = 'id';

    /**
     * Get the time logs for the project.
     */
    public function timeLogs(): HasMany
    {
        return $this->hasMany(TimeLog::class);
    }

    /**
     * Get the users associated with the project's time logs.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'time_logs')->withTimestamps();
    }
}
