<?php

namespace Tests\Unit;

use App\Events\CommentWritten;
use App\Listeners\CommentWrittenListener;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentWrittenListenerTest extends TestCase
{
    public function testIsCorrectAttachedToEvent(): void
    {
        Event::fake();
        Event::assertListening(
            CommentWritten::class,
            CommentWrittenListener::class
        );
    }
}
