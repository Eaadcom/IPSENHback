<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Like;
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

        if (
            (DB::table('likes')
            ->where('user_id', '=', $request['user_id_of_liked_user'])
            ->where('user_id_of_liked_user', '=', $request['user_id'])
            ->get()) != '[]'
        ) {
            $this->likeService->returnLike($request->all());
        } else {
            $this->likeService->create($request->all());
        }
        // Dit checkt nog niet of er al een bestaande like is in de DB, het kan momenteel dubbele records aanmaken.
    }
}
