<?php

use App\Models\Like;
use Laravel\Lumen\Testing\DatabaseMigrations;

class MessageControllerTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_authenticated_user_can_create_message()
    {

        $like = Like::factory()->create();
        $sender = $like->user;
        $likeMatch = $like->likeMatch;

        $postResponse = $this->actingAs($sender)->post('/api/v1/message', [
            'content' => 'Dit is een bericht',
            'like_match_id' => $likeMatch->id
        ]);

        $postResponse->assertResponseOk();

        $this->seeInDatabase('messages', [
            'content' => 'Dit is een bericht',
            'sender_id' => $sender->id,
            'like_match_id' => $likeMatch->id
        ]);
    }

    public function test_not_authenticated_user_cannot_create_message()
    {
        // ...
    }
}
