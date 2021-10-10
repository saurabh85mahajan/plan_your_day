<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory, SoftDeletes, MassPrunable;

    protected $fillable = ['title', 'priority', 'complexity'];

    public function project():BelongsTo
    {
        return $this->belongsTo(\App\Models\Project::class)->withDefault([
            'name' => 'No Project',
        ]);;
    }

    public function scopeDueToday(Builder $query):Builder
    {
        return $query->whereDate('tasks.created_at', Carbon::today());
    }

    public function scopeOlder(Builder $query): Builder
    {
        return $query->whereDate('tasks.created_at', '<', Carbon::today());
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('is_completed');
    }

    public function isCompleted(): bool
    {
        if( is_null( $this->is_completed)) {
            return false;
        }
        return true;
    }

    public function markComplete(): void
    {
        $this->is_completed = date('Y-m-d H:i:s');
        $this->save();
    }

    public function markPending(): void
    {
        $this->is_completed = null;
        $this->save();
    }

    public function duplicate(): void
    {
        $newTask = $this->replicate();
        $newTask->created_at = Carbon::now();
        $newTask->is_completed = null;
        $newTask->save();
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subMonths(3));
    }

}
