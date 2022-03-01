<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserBadge;
use Tests\TestCase;

class UserTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testDeactiveCurrentBadge()
    {
        UserBadge::factory()->create([
            'user_id' => $this->user->id,
            'current' => true
        ]);

        $this->user->deactiveBadges();
        $this->user->refresh();

        $this->assertNull($this->user->current_badge);
    }

}
