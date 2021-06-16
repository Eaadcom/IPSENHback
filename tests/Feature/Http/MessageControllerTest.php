<?php

namespace Tests\Feature\Http;

use App\Models\Like;
use App\Models\Message;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $message;
    private $likeMatchId;

    protected function setUp(): void
    {
        parent::setUp();

        $like = Like::factory()->create();
        $this->likeMatchId = $like->likeMatch->id;
        $this->message = Message::factory()->make([
            'like_match_id' => $this->likeMatchId,
            'sender_id' => $like->user->id
        ]);
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
        $this->postAsAuthenticated()->assertResponseOk();
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

    public function test_api_post_message_returns_unauthorized_for_user_not_in_match() {

        $user = User::factory()->create();
        $like = Like::factory() ->hasUser(User::factory())->create();
        $message = Message::factory()->make();
        $message->sender()->associate($user);

        $this->actingAs($this->message->sender)->post(
            route('message.post', ['id' => $like->likeMatch->id]),
            $this->message->toArray());

        $this->seeJson([
            'message' => 'Unauthorized'
        ]);
    }

    private function postAsAuthenticated() {
        return $this->actingAs($this->message->sender)->post(
            route('message.post', ['id' => $this->likeMatchId]),
            $this->message->toArray());
    }

    private function postAsNotAuthenticated() {
        return $this->post(
            route('message.post', ['id' => $this->likeMatchId]),
            $this->message->toArray());
    }
}
