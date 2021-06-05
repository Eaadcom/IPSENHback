<?php

namespace Tests\Feature\Http;

use App\Models\Like;
use App\Models\Message;
use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Events\MessageCreated;
use Tests\TestCase;

class MessageCreatedTest extends TestCase
{
    use DatabaseMigrations;

    private $message;
    private $likeMatch;

    // TODO: deze setup functie doet aardig wat, ik zou de content/generatie/ ect
    //  wat je ook doet verplaatsen naar een aparte functie
    protected function setUp(): void
    {
        parent::setUp();

        $content = 'Dit is een test bericht';
        $like = Like::factory()->create();
        $this->likeMatch = $like->likeMatch;
        $this->message = Message::factory()->create([
            'like_match_id' => $like->likeMatch->id,
            'sender_id' => $like->user->id,
            'content' => $content
        ]);
    }

    public function test_message_expected_event_can_fire()
    {
        $this->expectsEvents(MessageCreated::class);

        event(new MessageCreated($this->message, $this->likeMatch));
    }
}
