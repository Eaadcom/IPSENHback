<?php

namespace Tests\Unit\Services;

use App\Events\MessageCreated;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Like;
use App\Models\LikeMatch;
use App\Models\Message;
use App\services\MessageService;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MessageServiceTest extends Testcase
{
    /**
     * @var MessageService
     */
    private $service;
    private $like;
    private $message;
    private $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MessageService();

        $this->like = Like::factory()->create();
        $this->message = new Message([
            'content' => 'Test',
            'like_match_id' => $this->like->likeMatch->id,
            'created_at' => Carbon::now(),
            'sender_id' => $this->like->user->id
        ]);

        $this->request = new StoreMessageRequest([$this->message]);
    }

    public function test_service_create_function_stores_like_match_in_the_database()
    {
        $this->actingAs($this->like->user);
        $this->service->create($this->message);

        $this->seeInDatabase('messages', ['id' => $this->message->id]);
    }

    public function test_service_broadcast_should_fire_message_created_event()
    {
        $this->expectsEvents(MessageCreated::class);

        $this->service->broadcast($this->request);
    }

    public function test_service_broadcast_should_return_message_object()
    {
        $result = $this->service->broadcast($this->request);

        $this->assertInstanceOf(Message::class, $result);
    }

    public function test_service_save_should_store_message_in_database()
    {
        /** @var LikeMatch $likeMatch */
        $likeMatch = LikeMatch::query()->where('id', $this->message->like_match_id)->first();

        $this->actingAs($this->like->user);
        $this->service->save($this->message, $likeMatch);

        $this->seeInDatabase('messages', ['id' => $this->message->id]);
    }
}
