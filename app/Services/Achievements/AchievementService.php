<?php

namespace App\Services\Achievements;

use App\Events\AchievementUnlocked;
use App\Models\User;
use App\Models\UserAchievement;

abstract class AchievementService
{
    public string $name;

    public string $key;

    protected array $rules;

    public function handle(User $user): void
    {
        foreach ($this->rules as $key => $rule) {
            if ($this->validateRule($key, $this->getAmountToAchievement($user))) {
                $userAchievement = $this->getAchievementByUserAndRule($user, $rule);

                if (! $userAchievement) {
                    $this->createAchievement($user, $rule);
                    event(new AchievementUnlocked($this->name, $user));
                }
            }
        }
    }

    protected function validateRule(int $currentKey, int $userAmount): bool
    {
        $rule = $this->rules[$currentKey];
        $nextScore = $this->rules[$currentKey + 1] ?? null;

        return (! $nextScore || $userAmount < $nextScore)
            && $userAmount >= $rule;
    }

    protected function createAchievement(User $user, int $rule): void
    {
        UserAchievement::create([
            'user_id' => $user->id,
            'achievement_key' => $this->key,
            'achievement_message' => $this->getAchievementMessage($rule)
        ]);
    }

    protected function getAchievementByUserAndRule(User $user, $rule): ?UserAchievement
    {
        return UserAchievement::where('user_id', $user->id)
            ->where('achievement_key', $this->key)
            ->where('achievement_message', $this->getAchievementMessage($rule))
            ->first();
    }

    public function getNextAvailable(User $user): ?string
    {
        $currentAchievementsCount = $this->getAmountToAchievement($user);

        foreach ($this->rules as $rule) {
            $differenceFromCurrent = $currentAchievementsCount - $rule;

            if ($differenceFromCurrent < 0) {
                return $this->getAchievementMessage($rule);
            }
        }

        return null;
    }

    abstract protected function getAchievementMessage(int $watchedLessons): string;

    abstract protected function getAmountToAchievement(User $user): int;
}
