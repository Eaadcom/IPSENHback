<?php

namespace Tests\Feature\Http;

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class CodesnippetControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_authenticated_user_can_create_codesnippet()
    {

        $user = User::factory()->create();

        // Todo: Probeerd de AAA methodiek te volgen, Arrange, Act & Assert.
        //  dus eerst variabelen aanmaken en dan functies aanroepen roepen.
        //  Probeer dat in hoevere je kunt.

        // TODO: maak gebruik van route('naam.van.route')
        //  check hoe ik dat gedaan heb bij AuthControllerTest & web.php
        $postResponse = $this->actingAs($user)->post('/api/v1/codesnippet', [
            'content' => 'Dit is een codesnippet',
            'language' => 'C#',
            'theme' => 'Darcula'
        ]);

        // probeer 1 assert te hebben per functie.
        // 2 assert = twee test, status = 200 & data wordt opgeslagen in db.
        $postResponse->assertResponseOk();
        $this->seeInDatabase('codesnippets', [
            'content' => 'Dit is een codesnippet',
            'language' => 'C#',
            'theme' => 'Darcula'
        ]);
    }

    public function todo_test_not_authenticated_user_cannot_create_message()
    {
        // ...
    }
}

