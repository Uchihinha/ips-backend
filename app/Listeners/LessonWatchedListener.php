<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Services\Achievements\LessonsWatchedAchievementService;

class LessonWatchedListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        $service = app(LessonsWatchedAchievementService::class);

        if ($service->isAlreadyWatched($event->user, $event->lesson)) {
            return;
        }

        $service->createInstance($event->user, $event->lesson);
        $service->handle($event->user);
    }
}
