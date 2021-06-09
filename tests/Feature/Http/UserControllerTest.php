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

    // TODO: deze setup functie doet aardig wat, ik zou de content/generatie/ ect
    //  wat je ook doet verplaatsen naar een aparte functie
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

    public function test_api_post_like_returns_200()
    {
        $requestingUserid = $this->userThatIsRequesting->id;
        $this->getPotentialMatches($requestingUserid)->assertResponseOk();
    }

    public function test_api_get_potential_matches_returns_404()
    {
        $faultyRequestingUserId = rand();
        $this->getPotentialMatches($faultyRequestingUserId)->assertResponseStatus(404);
    }

    public function test_api_get_returns_200()
    {
        $requestedUserid = $this->userThatIsRequesting->id;
        $this->getUser($requestedUserid)->assertResponseOk();
    }

    public function test_api_get_returns_404()
    {
        $faultyRequestedUserId = rand();
        $this->getUser($faultyRequestedUserId)->assertResponseStatus(404);
    }

    private function getUser(int $id)
    {
        return $this->actingAs($this->userThatIsRequesting)->get(route('user.get', ['id' => $id]));
    }

    private function getPotentialMatches(int $id)
    {
        return $this->actingAs($this->userThatIsRequesting)->get(route('user.getPotentialMatches', ['id' => $id]));
    }
}
