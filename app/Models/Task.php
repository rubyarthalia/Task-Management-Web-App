<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Relationships

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeFilterStatus($query, ?string $status)
    {
        if (!$status) return $query;
        return $query->where('status', $status);
    }

    public function scopeFilterPriority($query, ?string $priority)
    {
        if (!$priority) return $query;
        return $query->where('priority', $priority);
    }

    public function scopeFilterCategory($query, ?int $categoryId)
    {
        if (!$categoryId) return $query;
        return $query->where('category_id', $categoryId);
    }

    public function scopeSorted($query, string $sort = 'created_at', string $direction = 'desc', bool $pushCompletedLast = false)
    {
        $allowed = ['created_at', 'due_date', 'priority', 'title', 'status'];
        $sort = in_array($sort, $allowed) ? $sort : 'created_at';
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'desc';

        // Push completed tasks to the bottom regardless of sort
        if ($pushCompletedLast) {
            $query = $query->orderByRaw("CASE WHEN status = 'completed' THEN 1 ELSE 0 END ASC");
        }

        // Custom priority ordering
        if ($sort === 'priority') {
            // Use FIELD to map priority to explicit ordering (high -> medium -> low)
            $query = $query->orderByRaw("FIELD(priority, 'high', 'medium', 'low') " . ($direction === 'asc' ? 'ASC' : 'DESC'));
            return $query;
        }

        return $query->orderBy($sort, $direction);
    }

    // Helpers

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted();
    }

    public function priorityColor(): string
    {
        return match ($this->priority) {
            'high'   => 'red',
            'medium' => 'amber',
            'low'    => 'emerald',
            default  => 'gray',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'completed'   => 'emerald',
            'in_progress' => 'blue',
            'pending'     => 'gray',
            default       => 'gray',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'in_progress' => 'In Progress',
            'completed'   => 'Completed',
            'pending'     => 'Pending',
            default       => ucfirst($this->status),
        };
    }
}