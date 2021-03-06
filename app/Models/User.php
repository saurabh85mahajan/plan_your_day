<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects():HasMany
    {
        return $this->hasMany(\App\Models\Project::class);
    }

    public function tasks():HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Project::class);
    }

    protected static function booted():void
    {
        static::created(function ($user) {
            $project = $user->projects()->create([
                'name' => 'No Project',
                'notes' => 'Default Project.'
            ]);
            $project->is_default = 1;
            $project->save();
        });
    }
}
