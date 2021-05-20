<?php

use App\Events\Event;
use App\Models\Like;
use App\Models\Message;
use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Events\MessageCreated;

class MessageCreatedTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        Like::factory()->create();
        $message = Message::factory()->create([
            'like_match_id' => 1,
            'sender_id' => 1,
            'content' => 'test'
        ]);

        $this->expectsEvents(MessageCreated::class);

        $event = event(new MessageCreated($message));
    }
}
