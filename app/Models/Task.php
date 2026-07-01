<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'module_name',
        'status',
        'priority',
        'due_date',
        'completed_at',
        'is_blocked',
        'blocker_description',
        'milestone_id',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'completed_at' => 'datetime',
            'is_blocked' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class)->latest();
    }

    public function labels(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'label_task');
    }

    public function dependencies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id');
    }

    public function dependents(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_task_id', 'task_id');
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    public function setDueDateAttribute($value)
    {
        if (!$value) {
            $this->attributes['due_date'] = null;
            return;
        }
        $date = \Illuminate\Support\Carbon::parse($value);
        if ($date->hour === 0 && $date->minute === 0 && $date->second === 0) {
            $date->endOfDay();
        }
        $this->attributes['due_date'] = $date;
    }

    public function getFormattedDueDateAttribute(): string
    {
        if (!$this->due_date) {
            return '';
        }
        $timeStr = $this->due_date->format('H:i:s');
        if ($timeStr === '23:59:59' || $timeStr === '00:00:00') {
            return $this->due_date->format('M d, Y');
        }
        return $this->due_date->format('M d, Y H:i');
    }

    public function getFormattedDueDateShortAttribute(): string
    {
        if (!$this->due_date) {
            return '';
        }
        $timeStr = $this->due_date->format('H:i:s');
        if ($timeStr === '23:59:59' || $timeStr === '00:00:00') {
            return $this->due_date->format('M d');
        }
        return $this->due_date->format('M d H:i');
    }

    public function isOverdue(): bool
    {
        if (!$this->due_date || $this->status === 'done') {
            return false;
        }

        $dueDate = $this->due_date->copy();
        if ($dueDate->hour === 0 && $dueDate->minute === 0 && $dueDate->second === 0) {
            $dueDate->endOfDay();
        }

        return $dueDate->isPast();
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->isOverdue();
    }
}
