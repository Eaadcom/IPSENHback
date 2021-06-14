<?php

namespace Tests\Feature\Http;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    use DatabaseMigrations;

    private $userThatIsRequesting;
    private $userThatIsPotentialMatch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->generateUsers();
    }

    private function generateUsers(){
        $this->userThatIsRequesting = User::factory()->create([
            'gender' => 'male',
            'age_range_top' => 20,
            'age_range_bottom' => 20,
            'date_of_birth' => '1998-1-1',
            'interest' => 'female'
        ]);
        $this->userThatIsPotentialMatch = User::factory()->create([
            'gender' => 'female',
            'age_range_top' => 20,
            'age_range_bottom' => 20,
            'date_of_birth' => '1998-1-1',
            'interest' => 'male'
        ]);
    }

    public function test_api_get_potential_matches_returns_200()
    {
        $this->getPotentialMatchesAsAuthenticatedUser($this->userThatIsRequesting->id)->assertResponseOk();
    }

    public function test_api_get_potential_matches_returns_404()
    {
        $faultyRequestingUserId = rand();
        $this->getPotentialMatchesAsAuthenticatedUser($faultyRequestingUserId)->assertResponseStatus(404);
    }

    public function test_api_get_returns_200()
    {
        $this->getUserAsAuthenticatedUser($this->userThatIsPotentialMatch->id)->assertResponseOk();
    }

    public function test_api_get_returns_404()
    {
        $faultyRequestedUserId = rand();
        $this->getUserAsAuthenticatedUser($faultyRequestedUserId)->assertResponseStatus(404);
    }

    public function test_api_get_user_returns_json_when_not_authenticated()
    {
        $this->getUserAsNotAuthenticatedUser($this->userThatIsPotentialMatch->id)->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    public function test_api_get_potentialmatches_returns_json_when_not_authenticated()
    {
        $this->getPotentialMatchesAsNotAuthenticatedUser($this->userThatIsRequesting->id)->seeJsonEquals([
            'message' => 'Unauthorized'
        ]);
    }

    private function getUserAsAuthenticatedUser(int $id)
    {
        return $this->actingAs($this->userThatIsRequesting)->get(route('user.get', ['id' => $id]));
    }

    private function getUserAsNotAuthenticatedUser(int $id)
    {
        return $this->get(route('user.get', ['id' => $id]));
    }

    private function getPotentialMatchesAsAuthenticatedUser(int $id)
    {
        return $this->actingAs($this->userThatIsRequesting)->get(route('user.getPotentialMatches', ['id' => $id]));
    }

    private function getPotentialMatchesAsNotAuthenticatedUser(int $id)
    {
        return $this->get(route('user.getPotentialMatches', ['id' => $id]));
    }
}
