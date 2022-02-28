<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Models\Comment;
use App\Models\User;
use App\Services\Achievements\CommentsWrittenAchievementsSerivce;
use Tests\TestCase;

class CommentsWrittenAchievementsSerivceTest extends TestCase
{
     private CommentsWrittenAchievementsSerivce $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new CommentsWrittenAchievementsSerivce();
    }

    private function generateUserComments(int $amount): User
    {
        $user = User::factory()->create();
        Comment::factory()->count($amount)->create(['user_id' => $user->id]);

        return $user;
    }

    public function testUnlockFirstCommentWrittenAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        $user = $this->generateUserComments(1);

        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => $this->service->key,
            'achievement_message' => 'First Comment Written'
        ]);
    }

    public function testUnlock3LessonsWatchedAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        $user = $this->generateUserComments(3);

        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => $this->service->key,
            'achievement_message' => '3 Comments Written'
        ]);
    }

    public function testUnlock5LessonsWatchedAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        $user = $this->generateUserComments(5);

        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => $this->service->key,
            'achievement_message' => '5 Comments Written'
        ]);
    }

    public function testUnlock10LessonsWatchedAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        $user = $this->generateUserComments(10);

        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => $this->service->key,
            'achievement_message' => '10 Comments Written'
        ]);
    }

    public function testUnlock20LessonsWatchedAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        $user = $this->generateUserComments(20);

        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => $this->service->key,
            'achievement_message' => '20 Comments Written'
        ]);
    }
}
