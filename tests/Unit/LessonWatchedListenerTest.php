<?php

namespace Tests\Unit;

use App\Events\LessonWatched;
use App\Listeners\LessonWatchedListener;
use App\Models\UserLesson;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LessonWatchedListenerTest extends TestCase
{
    public function testShouldNotCreateIfAlreadyExists(): void
    {
        $userLesson = UserLesson::factory()->create([
            'watched' => true
        ]);

        $event = new LessonWatched($userLesson->lesson, $userLesson->user);
        (new LessonWatchedListener())->handle($event);

        $this->assertEmpty($userLesson->user->userAchievements);
    }

    public function testIsCorrectAttachedToEvent(): void
    {
        Event::fake();
        Event::assertListening(
            LessonWatched::class,
            LessonWatchedListener::class
        );
    }
}
