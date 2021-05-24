<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Like;

class LikeControllerTest extends TestCase
{

    use DatabaseMigrations;

    private $likingUser;
    private $likedUser;
    private $like;
    private $postLikeEndpoint = '/api/v1/like';

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

    public function test_api_post_like_returns_200(){
        $this->postLikeAsAuthenticatedUser($this->like->toArray())->assertResponseOk();
    }

    public function test_api_post_like_returns_422(){
        $faultyLike = [];
        $this->postLikeAsAuthenticatedUser($faultyLike)->assertResponseStatus(422);
    }

    private function postLikeAsAuthenticatedUser(array $like)
    {
        return $this->actingAs($this->likedUser)->post($this->postLikeEndpoint, $like);
    }
}
