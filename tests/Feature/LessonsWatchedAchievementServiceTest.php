<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Models\Lesson;
use App\Services\Achievements\LessonsWatchedAchievementService;
use App\Models\User;
use App\Models\UserLesson;
use Tests\TestCase;

class LessonsWatchedAchievementServiceTest extends TestCase
{
    private LessonsWatchedAchievementService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new LessonsWatchedAchievementService();
    }

    public function testIsAlreadyWatched(): void
    {
        $userLesson = UserLesson::factory()->create([
            'watched' => true
        ]);

        $this->assertTrue($this->service->isAlreadyWatched($userLesson->user, $userLesson->lesson));
    }

    public function testIsNotAlreadyWatched(): void
    {
        $userLesson = UserLesson::factory()->create([
            'watched' => false
        ]);

        $this->assertFalse($this->service->isAlreadyWatched($userLesson->user, $userLesson->lesson));
    }

    private function generateUserLessons(int $amount): array
    {
        $user = User::factory()->create();
        UserLesson::factory()->count($amount)->create([
            'user_id' => $user->id,
            'watched' => true
        ]);

        $lesson = Lesson::factory()->create();

        return [$user, $lesson];
    }

    public function testUnlockFirstLessonWatchedAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        $this->service->createInstance($user, $lesson);
        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => LessonsWatchedAchievementService::KEY,
            'achievement_message' => 'First Lesson Watched'
        ]);
    }

    public function testUnlock5LessonsWatchedAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        [$user, $lesson] = $this->generateUserLessons(4);

        $this->service->createInstance($user, $lesson);
        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => LessonsWatchedAchievementService::KEY,
            'achievement_message' => '5 Lessons Watched'
        ]);
    }

    public function testUnlock10LessonsWatchedAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        [$user, $lesson] = $this->generateUserLessons(9);

        $this->service->createInstance($user, $lesson);
        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => LessonsWatchedAchievementService::KEY,
            'achievement_message' => '10 Lessons Watched'
        ]);
    }

    public function testUnlock25LessonsWatchedAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        [$user, $lesson] = $this->generateUserLessons(24);

        $this->service->createInstance($user, $lesson);
        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => LessonsWatchedAchievementService::KEY,
            'achievement_message' => '25 Lessons Watched'
        ]);
    }

    public function testUnlock50LessonsWatchedAchievement(): void
    {
        $this->expectsEvents(AchievementUnlocked::class);

        [$user, $lesson] = $this->generateUserLessons(49);

        $this->service->createInstance($user, $lesson);
        $this->service->handle($user);

        $this->assertDatabaseHas('user_achievements', [
            'user_id' => $user->id,
            'achievement_key' => LessonsWatchedAchievementService::KEY,
            'achievement_message' => '50 Lessons Watched'
        ]);
    }
}
