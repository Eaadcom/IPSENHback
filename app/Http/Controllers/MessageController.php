<?php


namespace App\Http\Controllers;


use App\Models\Message;
use App\services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function post(Request $request): string
    {

     $this->validate($request, [
            'content' => [
                'required'
            ],
            'like_match_id' => [
                'required',
                'exists:like_matches,id',
                Rule::exists('likes')->where(function ($query) use ($request) {
                    return $query->where(function ($query) {
                        return $query->where('user_id', auth()->id())->orWhere('user_id_of_liked_user', auth()->id());
                    })->where('like_match_id', $request->get('like_match_id'));
                })
            ],
        ]);

        $this->messageService->create($request->all());

        return response()->json(['message' => 'Successfully created the message.']);
    }
}
