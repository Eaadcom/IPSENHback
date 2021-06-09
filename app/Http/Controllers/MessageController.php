<?php


namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\services\MessageService;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    /**
     * @var MessageService
     */
    private $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function post(StoreMessageRequest $request): JsonResponse
    {
        $message = $this->messageService->broadcast($request);

        $this->messageService->create($message);

        return response()->json(['message' => 'Successfully created the message.']);
    }
}
