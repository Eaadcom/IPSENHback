<?php

use App\Models\Like;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class LikeMatchControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $like;
    private $getByIdEndpoint = '/api/v1/likematch/';
    private $getAllEndpoint = '/api/v1/likematch';
    private $deleteByIdEndpoint = '/api/v1/likematch/';

    protected function setUp(): void
    {
        parent::setUp();

        $this->like = Like::factory()->create();
    }

    public function test_api_get_like_match_returns_200()
    {
        $existingId = $this->like->likeMatch->id;
        $this->getById($existingId)->assertResponseOk();
    }

    public function test_api_get_like_match_returns_404()
    {
        $nonExistingId = rand();
        $this->getById($nonExistingId)->assertResponseStatus(404);
    }

    public function test_api_get_like_matches_of_user_returns_200()
    {
        $this->getOfUser($this->like->user)->assertResponseOk();
    }

    public function test_api_delete_match_returns_200(){
        $existingId = $this->like->likeMatch->id;
        $this->deleteByIdAsAuthenticatedUser($existingId)->assertResponseOk();
    }

    public function test_api_delete_match_returns_404(){
        $nonExistingId = rand();
        $this->deleteByIdAsAuthenticatedUser($nonExistingId)->assertResponseStatus(404);
    }

    private function deleteByIdAsAuthenticatedUser($id)
    {
        return $this->actingAs($this->like->user)->delete($this->deleteByIdEndpoint . $id);
    }

    private function getById($id)
    {
        return $this->actingAs($this->like->user)->get($this->getByIdEndpoint . $id);
    }

    private function getOfUser(User $user)
    {
        return $this->actingAs($user)->get($this->getAllEndpoint);
    }
}
