<?php

namespace Tests\Feature\Http;

use App\Models\Codesnippet;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class CodesnippetControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $codesnippet;
    private $authUser;
    protected function setUp(): void
    {
        parent::setUp();
        $this->authUser = User::factory()->create();
    }

    public function test_authenticated_user_can_create_codesnippet()
    {
        $this->postAsAuthenticated()->seeJsonContains([
            'message' => 'codesnippet succesfully created.'
            ]);
    }

    public function test_not_authenticated_user_cannot_create_codesnippet()
    {
        $this->postAsNotAuthenticated()->assertResponseStatus(401);
    }

    public function test_api_post_codesnippet_returns_json_when_not_authenticated()
    {
        $this->postAsNotAuthenticated()->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    public function test_api_post_codesnippet_creates_codesnippet_in_database_when_authenticated()
    {
        $this->postAsAuthenticated();

        $this->seeInDatabase('codesnippets', [
            'content' => $this->codesnippet->content,
            'language' => $this->codesnippet->language,
            'theme' => $this->codesnippet->theme
        ]);
    }

    public function test_api_post_codesnippet_doesnt_create_codesnippet_in_database_when_not_authenticated()
    {
        $this->postAsNotAuthenticated();

        $this->missingFromDatabase('codesnippets', [
            'content' => $this->codesnippet->content,
            'language' => $this->codesnippet->language,
            'theme' => $this->codesnippet->theme
        ]);
    }

    public function test_api_get_codesnippetByUserId_user_can_get_codesnippets_by_id()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($this->authUser)->get(route('codesnippet.getByUserId', ['userId' => $user->id]));
        $response->assertResponseOk();
    }

    public function test_api_get_codesnippetByUserId_returns_json_when_not_authenticated()
    {
        $user = User::factory()->create();
        $response = $this->get(route('codesnippet.getByUserId', ['userId' => $user->id]));
        $response->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    public function test_api_get_codesnippetByUserId_does_not_return_codesnippets_of_user_by_id_when_not_authenticated()
    {
        $user = User::factory()->create();
        $codesnippet  = $this->createCodesnippetForUser($user);

        $response = $this->get(route('codesnippet.getByUserId', ['userId' => $user->id]));
        $response->seeJsonDoesntContains($codesnippet->toArray());
    }

    public function test_api_get_codesnippetByUserId_returns_codesnippets_of_user_by_id_when_authenticated()
    {
        $user = User::factory()->create();
        $codesnippet  = $this->createCodesnippetForUser($user);

        $response = $this->actingAs($this->authUser)->get(route('codesnippet.getByUserId', ['userId' => $user->id]));
        $response->seeJsonContains($codesnippet->toArray());
    }

    public function test_api_get_codesnippetByAuthId_user_can_get_codesnippets_when_authenticated()
    {
        $response = $this->actingAs($this->authUser)->get(route('codesnippet.getByAuthId'));
        $response->assertResponseOk();
    }

    public function test_api_get_codesnippetByAuthId_returns_json_when_not_authenticated()
    {
        $response = $this->get(route('codesnippet.getByAuthId'));
        $response->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    public function test_api_get_codesnippetByAuthId_does_not_return_codesnippets_of_auth_user_when_not_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $response = $this->get(route('codesnippet.getByAuthId'));
        $response->seeJsonDoesntContains($codesnippet->toArray());
    }

    public function test_api_get_codesnippetByAuthId_returns_codesnippets_of_auth_user_when_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $response = $this->actingAs($this->authUser)->get(route('codesnippet.getByAuthId'));
        $response->seeJsonContains($codesnippet->toArray());
    }

    public function test_api_put_user_can_update_codesnippets_when_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $newContent = $this->faker->paragraph;
        $codesnippet->content = $newContent;

        $response = $this->actingAs($this->authUser)->
            put(route('codesnippet.put', ['id' => $codesnippet->id]), $codesnippet->toArray());
        $response->seeJsonContains([
            'message' => 'Codesnippet succesfully updated.'
        ]);
    }

    public function test_api_put_returns_json_when_not_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $response = $this->put(route('codesnippet.put', ['id' => $codesnippet->id]), $codesnippet->toArray());
        $response->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    public function test_api_put_updates_codesnippet_when_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $newContent = $this->faker->paragraph;
        $codesnippet->content = $newContent;

        $this->actingAs($this->authUser)
            ->put(route('codesnippet.put', ['id' => $codesnippet->id]), $codesnippet->toArray());
        $response = $this->actingAs($this->authUser)->get(route('codesnippet.getByAuthId'));
        $response->seeJsonContains($codesnippet->only('content'));
    }

    public function test_api_put_does_not_update_codesnippet_when_not_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $newContent = $this->faker->paragraph;
        $codesnippet->content = $newContent;

        $this->put(route('codesnippet.put', ['id' => $codesnippet->id]), $codesnippet->toArray());
        $response = $this->actingAs($this->authUser)->get(route('codesnippet.getByAuthId'));
        $response->seeJsonDoesntContains($codesnippet->only('content'));
    }

    public function test_api_delete_user_can_delete_codesnippets_when_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $response = $this->actingAs($this->authUser)->
            delete(route('codesnippet.delete', ['id' => $codesnippet->id]));
        $response->seeJsonContains([
            'message' => 'Codesnippet succesfully deleted.'
        ]);
    }

    public function test_api_delete_returns_json_when_not_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $response = $this->delete(route('codesnippet.delete', ['id' => $codesnippet->id]));
        $response->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    public function test_api_delete_does_not_delete_codesnippet_when_not_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $this->delete(route('codesnippet.delete', ['id' => $codesnippet->id]));
        $response = $this->actingAs($this->authUser)->get(route('codesnippet.getByAuthId'));
        $response->seeJsonContains($codesnippet->toArray());
    }

    public function test_api_delete_deletes_codesnippet_when_authenticated()
    {
        $codesnippet  = $this->createCodesnippetForUser($this->authUser);

        $this->actingAs($this->authUser)->
            delete(route('codesnippet.delete', ['id' => $codesnippet->id]));
        $response = $this->actingAs($this->authUser)->get(route('codesnippet.getByAuthId'));
        $response->seeJsonDoesntContains($codesnippet->toArray());
    }


    private function postAsNotAuthenticated() {
        $this->codesnippet = Codesnippet::factory()->make([
            'user_id' => $this->authUser->id,
        ]);
        return $this->post(route('codesnippet.post'), $this->codesnippet->toArray());
    }

    private function postAsAuthenticated() {
        $this->codesnippet = Codesnippet::factory()->make([
            'user_id' => $this->authUser->id,
        ]);
        return $this->actingAs($this->authUser)->post(route('codesnippet.post'), $this->codesnippet->toArray());
    }

    private function createCodesnippetForUser(User $user) {
        return Codesnippet::factory()->create([
            'user_id' => $user->id,
        ])->first();
    }

}

