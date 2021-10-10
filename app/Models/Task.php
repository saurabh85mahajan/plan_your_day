<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes, MassPrunable;

    protected $fillable = ['title', 'priority', 'complexity'];

    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class)->withDefault([
            'name' => 'No Project',
        ]);;
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('tasks.created_at', Carbon::today());
    }

    public function scopeOlder($query)
    {
        return $query->whereDate('tasks.created_at', '<', Carbon::today());
    }

    public function scopePending($query)
    {
        return $query->whereNull('is_completed');
    }

    public function isCompleted()
    {
        if( is_null( $this->is_completed)) {
            return false;
        }
        return true;
    }

    public function markComplete()
    {
        $this->is_completed = date('Y-m-d H:i:s');
        $this->save();
    }

    public function markPending()
    {
        $this->is_completed = null;
        $this->save();
    }

    public function duplicate()
    {
        $newTask = $this->replicate();
        $newTask->created_at = Carbon::now();
        $newTask->is_completed = null;
        $newTask->save();
    }

    public function prunable()
    {
        return static::where('created_at', '<=', now()->subMonths(3));
    }

}
