<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Models\User;

class UserControllerTest extends TestCase
{

    use DatabaseMigrations;

    private $userThatIsRequesting;
    private $userThatIsPotentialMatch;
    private $getPotentialMatchesEndpoint = '/api/v1/user/potentialmatches/';
    private $getUserEndpoint = '/api/v1/user/';

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_api_post_like_returns_200(){
        $requestingUserid = $this->userThatIsRequesting->id;
        $this->getPotentialMatches($requestingUserid)->assertResponseOk();
    }

    public function test_api_get_potential_matches_returns_404(){
        $faultyRequestingUserId = rand();
        $this->getPotentialMatches($faultyRequestingUserId)->assertResponseStatus(404);
    }

    public function test_api_get_returns_200(){
        $requestedUserid = $this->userThatIsRequesting->id;
        $this->getUser($requestedUserid)->assertResponseOk();
    }

    public function test_api_get_returns_404(){
        $faultyRequestedUserId = rand();
        $this->getUser($faultyRequestedUserId)->assertResponseStatus(404);
    }

    private function getUser(int $id){
        return $this->actingAs($this->userThatIsRequesting)->get($this->getUserEndpoint . $id);
    }

    private function getPotentialMatches(int $id)
    {
        return $this->actingAs($this->userThatIsRequesting)->get($this->getPotentialMatchesEndpoint . $id);
    }
}
