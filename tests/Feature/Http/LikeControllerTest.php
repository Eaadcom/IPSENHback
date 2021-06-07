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
    // TODO: maak gebruik van route('naam.van.route')
    //  check hoe ik dat gedaan heb bij AuthControllerTest & web.php
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

    // Ik persoonlijk, vind dit niet een goede functie, het geen wat je functie doet is een andere functie aanroepen.
    // hierdoor vind ik je test niet echt clean.
    // je haalt duplicate code weg maar ook leesbaarheid in mijn ogen.
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
        return $this->actingAs($this->likedUser)->post($this->postLikeEndpoint, $like);
    }
}
