<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class CodesnippetControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_authenticated_user_can_create_codesnippet()
    {

        $user = User::factory()->create();

        $postResponse = $this->actingAs($user)->post('/api/v1/codesnippet', [
            'content' => 'Dit is een codesnippet',
            'language' => 'C#',
            'theme' => 'Darcula'
        ]);

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

