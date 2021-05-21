<?php

use App\Models\Like;
use App\Models\Message;
use Laravel\Lumen\Testing\DatabaseMigrations;

class MessageControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $message;
    private $postEndpoint = '/api/v1/message';

    protected function setUp(): void
    {
        parent::setUp();

        $like = Like::factory()->create();

        $this->message = Message::factory()->make([
            'like_match_id' => $like->likeMatch->id,
            'sender_id' => $like->user->id
        ]);
    }

    public function test_api_post_message_returns_status_200()
    {
        $this->postAsAuthenticated()->assertResponseOk();
    }

    public function test_api_post_message_creates_message_in_database()
    {
        $this->postAsAuthenticated();

        $this->seeInDatabase('messages', [
            'content' => $this->message->content,
            'sender_id' => $this->message->sender->id,
            'like_match_id' => $this->message->likeMatch->id
        ]);
    }

    public function test_api_post_message_returns_status_422()
    {
        $this->postAsNotAuthenticated()->assertResponseStatus(422);
    }

    public function test_api_post_message_doesnt_create_message_in_database()
    {
        $this->postAsNotAuthenticated();

        $this->missingFromDatabase('messages', [
            'content' => $this->message->content,
            'sender_id' => $this->message->sender->id,
            'like_match_id' => $this->message->likeMatch->id
        ]);
    }

    private function postAsAuthenticated() {
        return $this->actingAs($this->message->sender)->post($this->postEndpoint, $this->message->toArray());
    }

    private function postAsNotAuthenticated() {
        return $this->post($this->postEndpoint, $this->message->toArray());
    }
}
