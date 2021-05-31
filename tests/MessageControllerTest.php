<?php

use App\Models\Like;
use App\Models\Message;
use Laravel\Lumen\Testing\DatabaseMigrations;

class MessageControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $message;
    private $postEndpoint = '/api/v1/like-match/';
    protected function setUp(): void
    {
        parent::setUp();

        $like = Like::factory()->create();
        $likeMatchid = $like->likeMatch->id;

        $this->message = Message::factory()->make([
            'like_match_id' => $likeMatchid,
            'sender_id' => $like->user->id
        ]);

        $this->postEndpoint .= $likeMatchid . '/message';
    }

    public function test_api_post_message_returns_status_200_when_authenticated()
    {
        $this->postAsAuthenticated()->assertResponseOk();
    }

    public function test_api_post_message_creates_message_in_database_when_authenticated()
    {
        $this->postAsAuthenticated();

        $this->seeInDatabase('messages', [
            'content' => $this->message->content,
            'sender_id' => $this->message->sender->id,
            'like_match_id' => $this->message->likeMatch->id
        ]);
    }

    public function test_api_post_message_returns_json_when_authenticated()
    {
        $response = $this->postAsAuthenticated();
        $response->seeJsonEquals([
            'message' => 'Successfully created the message.'
        ]);
    }

    public function test_api_post_message_returns_status_401_when_not_authenticated()
    {
        $this->postAsNotAuthenticated()->assertResponseStatus(401);
    }

    public function test_api_post_message_doesnt_create_message_in_database_when_not_authenticated()
    {
        $this->postAsNotAuthenticated();

        $this->missingFromDatabase('messages', [
            'content' => $this->message->content,
            'sender_id' => $this->message->sender->id,
            'like_match_id' => $this->message->likeMatch->id
        ]);
    }

    public function test_api_post_message_returns_json_when_not_authenticated()
    {
        $this->postAsNotAuthenticated()->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    private function postAsAuthenticated() {
        dump($this->message->toArray());
        return $this->actingAs($this->message->sender)->post($this->postEndpoint, $this->message->toArray());
    }

    private function postAsNotAuthenticated() {
        return $this->post($this->postEndpoint, $this->message->toArray());
    }
}
