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
        $this->postLike($this->like->toArray())->assertResponseOk();
    }

    public function test_api_post_like_returns_422(){
        $faultyLike = [];
        $this->postLike($faultyLike)->assertResponseStatus(422);
    }

    private function postLike(array $like)
    {
        return $this->post($this->postLikeEndpoint, $like);
    }
}
