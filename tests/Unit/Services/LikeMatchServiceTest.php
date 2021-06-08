<?php

namespace Tests\Unit\Services;

use App\Models\LikeMatch;
use App\services\LikeMatchService;
use Carbon\Carbon;
use Tests\TestCase;

class LikeMatchServiceTest extends TestCase
{
    /**
     * @var LikeMatchService
     */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new LikeMatchService();
    }

    public function test_service_create_function_stores_like_match_in_the_database()
    {
        $likeMatchId = $this->service->create();

        $this->seeInDatabase('like_matches', ['id' => $likeMatchId]);
    }

    public function test_service_delete_function_deletes_like_match_in_the_database()
    {
        $likeMatchId = $this->service->create();
        $this->service->delete($likeMatchId);

        $this->seeInDatabase('like_matches', [
            'id' => $likeMatchId,
            'deleted_at' => Carbon::now()
        ]);
    }

    public function test_service_getById_function_gets_like_match_from_database_by_id()
    {
        $likeMatchId = $this->faker->numberBetween(2);
        LikeMatch::factory()->create(['id' => $likeMatchId]);
        $actualMatch = $this->service->getById($likeMatchId);

        $this->assertNotNull($actualMatch);
        $this->assertInstanceOf(LikeMatch::class, $actualMatch);
    }

    public function test_service_getAllLikeMatchesOfAuthUser_function_returns_like_matches_of_auth_user()
    {
        $likeMatches = $this->service->getAllLikeMatchesOfAuthUser();

        $this->assertNotNull($likeMatches);
        $this->assertContainsOnlyInstancesOf(LikeMatch::class, $likeMatches);
    }

    public function test_service_getAllLikeMatchesOfAuthUser_function_returns_empty_array_when_there_are_no_like_matches()
    {
        $likeMatches = $this->service->getAllLikeMatchesOfAuthUser();

        $this->assertEmpty($likeMatches);
    }
}
