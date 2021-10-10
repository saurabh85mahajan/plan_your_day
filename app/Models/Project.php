<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'notes'];

    public function user():BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function tasks():HasMany
    {
        return $this->hasMany(\App\Models\Task::class);
    }

    public function scopeNonDefault(Builder $query): Builder
    {
        return $query->whereNull('is_default');
    }
}
