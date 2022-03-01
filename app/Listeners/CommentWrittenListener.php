<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Services\Achievements\CommentsWrittenAchievementsSerivce;

class CommentWrittenListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        $service = app(CommentsWrittenAchievementsSerivce::class);
        $service->handle($event->comment->user);
    }
}
