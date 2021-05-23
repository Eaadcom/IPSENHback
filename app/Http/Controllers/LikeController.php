<?php

namespace App\Http\Controllers;

use App\services\LikeMatchService;
use Illuminate\Http\Request;
use App\services\LikeService;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{

    /**
     * @var LikeMatchService
     */
    private $likeMatchService;

    /**
     * @var LikeService
     */
    private $likeService;

    public function __construct(LikeService $likeService, LikeMatchService $likeMatchService)
    {
        $this->likeService = $likeService;
        $this->likeMatchService = $likeMatchService;
    }

    // Checkt niet of er al een bestaande like is in de DB, er kunnen dubbele records worden aangemaakt.
    public function post(Request $request): JsonResponse
    {
        $this->validate($request, [
            'user_id' => 'required',
            'user_id_of_liked_user' => 'required',
            'type' => 'required',
        ]);

        $userIdOfLikedUser = $request->get('user_id_of_liked_user');
        if ($this->likeService->checkIfLikeExists($userIdOfLikedUser)) {
            $this->likeService->returnLike($request->all());

            //TODO auth()->id() gebruiken ipv. user_id
            if ($this->likeService->checkIfThereIsAMatch($userIdOfLikedUser)
            ) {
                $matchId = $this->likeMatchService->create();
                $this->likeService->assignMatchId($request->all(), $matchId);
            }

        } else {
            $this->likeService->create($request->all());
        }

        return response()->json(['message' => 'Successfully created the like.']);
    }
}
