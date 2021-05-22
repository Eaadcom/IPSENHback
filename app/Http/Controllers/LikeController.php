<?php

namespace App\Http\Controllers;

use App\services\LikeMatchService;
use Illuminate\Http\Request;
use App\services\LikeService;

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
    public function post(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'user_id_of_liked_user' => 'required',
            'type' => 'required',
        ]);

        if (
            $this->likeService->checkIfLikeExists($request->all()))
        {
            // auth()->id() needs to be used in stead of passing the user_id constant

            $this->likeService->returnLike($request->all());

            if ($this->likeService->checkIfThereIsAMatch(
                [$request['user_id'], $request['user_id_of_liked_user']])
            )
            {
                $this->likeMatchService->create();
            }


        } else {
            $this->likeService->create($request->all());
        }

        return response()->json(['message' => 'Successfully created the like.']);
    }
}
