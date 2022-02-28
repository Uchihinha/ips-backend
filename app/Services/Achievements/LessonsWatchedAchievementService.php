<?php

namespace App\Services\Achievements;

use App\Events\AchievementUnlocked;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserLesson;

class LessonsWatchedAchievementService extends AchievementService
{
    public const KEY = 'lessons_watched';

    private array $rules = [1, 5, 10, 25, 50];

    public function __construct()
    {
        $this->name = 'Lessons Watched Achievement';
    }

    public function handle(User $user): void
    {
        foreach ($this->rules as $key => $rule) {
            if ($this->validateRule($key, $user->watched_lessons_count)) {
                $userAchievement = $this->getAchievementByUserAndRule($user, $rule);

                if (! $userAchievement) {
                    $this->createAchievement($user, $rule);
                    event(new AchievementUnlocked($this->name, $user));
                }
            }
        }
    }

    private function validateRule(int $currentKey, int $userAmount): bool
    {
        $rule = $this->rules[$currentKey];

        return (
            ! isset($this->rules[$currentKey + 1])
            || $userAmount < ($this->rules[$currentKey + 1])
        )
        && $userAmount >= $rule;
    }

    private function createAchievement(User $user, int $rule): void
    {
        UserAchievement::create([
            'user_id' => $user->id,
            'achievement_key' => self::KEY,
            'achievement_message' => $this->getAchievementMessage($rule)
        ]);
    }

    private function getAchievementByUserAndRule(User $user, $rule): ?UserAchievement
    {
        return UserAchievement::where('user_id', $user->id)
            ->where('achievement_key', self::KEY)
            ->where('achievement_message', $this->getAchievementMessage($rule))
            ->first();
    }

    public function createInstance(User $user, Lesson $lesson): void
    {
        UserLesson::updateOrCreate([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ],[
            'watched' => true
        ]);
    }

    public function isAlreadyWatched(User $user, Lesson $lesson): bool
    {
        return $user->userLessons()
            ->where('lesson_id', $lesson->id)
            ->whereWatched(true)
            ->exists();
    }

    private function getAchievementMessage(int $watchedLessons): string
    {
        if ($watchedLessons == 1) {
            return 'First Lesson Watched';
        }

        return "$watchedLessons Lessons Watched";
    }
}
