<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\services\LikeService;

class LikeController extends Controller
{

    /**
     * @var LikeService
     */
    private $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function post(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'user_id_of_liked_user' => 'required',
            'type' => 'required',
        ]);

        $this->likeService->create($request->all());

    }
}
