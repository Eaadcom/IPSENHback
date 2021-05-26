<?php

use App\Models\Like;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class LikeMatchControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $likeMatchId;
    private $likeUser;
    private $getByIdEndpoint = '/api/v1/likematch/';
    private $getAllEndpoint = '/api/v1/likematch';

    protected function setUp(): void
    {
        parent::setUp();

        $like = Like::factory()->create();
        $this->likeMatchId = $like->likeMatch->id;
        $this->likeUser = $like->user;
    }

    public function test_api_get_match_returns_status_200_when_authenticated_with_existing_id()
    {
        $this->getByIdAsAuthenticatedUser($this->likeMatchId)->assertResponseOk();
    }

    public function test_api_get_match_returns_json_with_messages_when_authenticated_with_existing_id()
    {
        $this->getByIdAsAuthenticatedUser($this->likeMatchId)->seeJsonStructure([
            'message' => [
                'messages'
                ]
        ]);
    }

    public function test_api_get_match_returns_status_404_when_authenticated_with_non_existing_id()
    {
        $nonExistingId = rand();
        $this->getByIdAsAuthenticatedUser($nonExistingId)->assertResponseStatus(404);
    }

    public function test_api_get_match_returns_json_with_error_message_when_authenticated_with_non_existing_id()
    {
        $nonExistingId = rand();
        $this->getByIdAsAuthenticatedUser($nonExistingId)->seeJsonEquals([
            'message' => 'Not found'
        ]);
    }

    public function test_api_get_match_returns_json_without_messages_when_authenticated_with_non_existing_id()
    {
        $nonExistingId = rand();
        $this->getByIdAsAuthenticatedUser($nonExistingId)->seeJsonDoesntContains([
            'message' => [
                'messages'
            ]
        ]);
    }

    public function test_api_get_match_returns_status_401_when_not_authenticated()
    {
        $this->get($this->getByIdEndpoint . $this->likeMatchId)->assertResponseStatus(401);
    }

    public function test_api_get_match_returns_json_with_error_message_when_not_authenticated()
    {
        $this->get($this->getByIdEndpoint . $this->likeMatchId)->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    public function test_api_get_matches_of_user_returns_status_200_when_authenticated()
    {
        $this->actingAs($this->likeUser)->get($this->getAllEndpoint)->assertResponseOk();
    }

    public function test_api_get_matches_of_user_returns_status_401_when_not_authenticated()
    {
        $this->getAllAsNotAuthenticatedUser()->assertResponseStatus(401);
    }

    public function test_api_get_matches_of_user_returns_json_with_error_message_when_not_authenticated()
    {
        $this->getAllAsNotAuthenticatedUser()->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    public function test_api_delete_match_returns_status_200_when_authenticated_with_existing_id(){
        $this->deleteByIdAsAuthenticatedUser($this->likeMatchId)->assertResponseOk();
    }

    public function test_api_delete_match_returns_json_when_authenticated_with_existing_id()
    {
        $this->deleteByIdAsAuthenticatedUser($this->likeMatchId)->seeJsonEquals([
            'message' => true
        ]);
    }

    public function test_api_delete_match_returns_status_404_when_authenticated_with_non_existing_id(){
        $nonExistingId = rand();
        $this->deleteByIdAsAuthenticatedUser($nonExistingId)->assertResponseStatus(404);
    }

    public function test_api_delete_match_returns_json_when_authenticated_with_non_existing_id()
    {
        $nonExistingId = rand();
        $this->deleteByIdAsAuthenticatedUser($nonExistingId)->seeJsonEquals([
            'message' => 'Not found'
        ]);
    }

    public function test_api_delete_match_returns_status_401_when_not_authenticated()
    {
        $this->deleteByIdAsNotAuthenticatedUser($this->likeMatchId)->assertResponseStatus(401);
    }

    public function test_api_delete_match_returns_json_when_not_authenticated()
    {
        $this->deleteByIdAsNotAuthenticatedUser($this->likeMatchId)->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    private function deleteByIdAsAuthenticatedUser($id)
    {
        return $this->actingAs($this->likeUser)->delete($this->getByIdEndpoint . $id);
    }

    private function deleteByIdAsNotAuthenticatedUser($id)
    {
        return $this->delete($this->getByIdEndpoint . $id);
    }

    private function getByIdAsAuthenticatedUser($id)
    {
        return $this->actingAs($this->likeUser)->get($this->getByIdEndpoint . $id);
    }

    private function getAllAsNotAuthenticatedUser()
    {
        return $this->get($this->getAllEndpoint);
    }
}
