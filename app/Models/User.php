<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function userLessons()
    {
        return $this->hasMany(UserLesson::class);
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'user_lessons', 'user_id', 'lesson_id');
    }

    public function watchedLessons()
    {
        return $this->lessons()->wherePivot('watched', true);
    }

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges', 'user_id', 'badge_id');
    }

    public function getWatchedLessonsCountAttribute(): int
    {
        return $this->watchedLessons()->count();
    }

    public function getCurrentBadgeAttribute(): ?Badge
    {
        return optional(
            $this->userBadges()
                ->where('current', true)
                ->latest()
                ->first()
        )->badge;
    }

    public function deactiveBadges(): void
    {
        $this->userBadges()->where('current', true)->update(['current' => false]);
    }
}
