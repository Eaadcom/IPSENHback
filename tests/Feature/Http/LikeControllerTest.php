<?php

namespace Tests\Feature\Http;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Like;
use Tests\TestCase;

class LikeControllerTest extends TestCase
{

    use DatabaseMigrations;

    private $likingUser;
    private $likedUser;
    private $like;

    protected function setUp(): void
    {
        parent::setUp();

        $this->likingUser = User::factory()->create();
        $this->likedUser = User::factory()->create();

        $this->like = Like::factory()->create([
            'user_id' => $this->likingUser->id,
            'user_id_of_liked_user' => $this->likedUser->id,
            'type' => 'like'
        ]);
    }

    public function test_authenticated_user_can_create_like()
    {
        $this->postLikeAsAuthenticatedUser($this->like->toArray())->seeJsonContains([
            'message' => 'Successfully created the like.'
        ]);
    }

    public function test_not_authenticated_user_cannot_create_like()
    {
        $this->postLikeAsNotAuthenticatedUser($this->like->toArray())->assertResponseStatus(401);
    }

    public function test_api_post_like_returns_json_when_not_authenticated()
    {
        $this->postLikeAsNotAuthenticatedUser($this->like->toArray())->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    public function test_api_post_like_creates_like_in_database_when_authenticated()
    {
        $this->postLikeAsAuthenticatedUser($this->like->toArray());

        $this->seeInDatabase('likes', [
            'user_id' => $this->like->user_id,
            'user_id_of_liked_user' => $this->like->user_id_of_liked_user,
            'type' => $this->like->type
        ]);
    }

    public function test_api_post_like_doesnt_create_like_in_database_when_not_authenticated()
    {
        $this->like->type = 'dislike';

        $this->postLikeAsNotAuthenticatedUser($this->like->toArray());

        $this->missingFromDatabase('likes', [
            'user_id' => $this->like->user_id,
            'user_id_of_liked_user' => $this->like->user_id_of_liked_user,
            'type' => $this->like->type
        ]);
    }

    public function test_api_post_like_returns_200()
    {
        $this->postLikeAsAuthenticatedUser($this->like->toArray())->assertResponseOk();
    }

    public function test_api_post_like_returns_422()
    {
        $faultyLike = [];
        $this->postLikeAsAuthenticatedUser($faultyLike)->assertResponseStatus(422);
    }

    private function postLikeAsAuthenticatedUser(array $like)
    {
        return $this->actingAs($this->likedUser)->post(route('like.post'), $like);
    }

    private function postLikeAsNotAuthenticatedUser(array $like) {
        return $this->post(route('like.post'), $like);
    }
}
