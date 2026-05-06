<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'client_name',
        'owner_id',
        'status',
        'priority',
        'due_date',
        'archived_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'archived_at' => 'datetime',
        ];
    }

    /**
     * Get auto-calculated progress based on completed tasks.
     */
    public function getProgressAttribute(): int
    {
        $totalTasks = $this->tasks()->count();
        
        if ($totalTasks === 0) {
            return 0;
        }
        
        $completedTasks = $this->tasks()->where('status', 'done')->count();
        
        return (int) round(($completedTasks / $totalTasks) * 100);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function completedTasks(): HasMany
    {
        return $this->tasks()->where('status', 'done');
    }

    /**
     * Members assigned to this project.
     */
    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withTimestamps();
    }

    /**
     * Get task breakdown for display.
     */
    public function getTaskBreakdown(): array
    {
        return [
            'total' => $this->tasks()->count(),
            'completed' => $this->tasks()->where('status', 'done')->count(),
            'in_progress' => $this->tasks()->where('status', 'doing')->count(),
            'todo' => $this->tasks()->where('status', 'todo')->count(),
            'recently_added' => $this->tasks()->where('created_at', '>=', now()->subWeek())->count(),
        ];
    }
}
