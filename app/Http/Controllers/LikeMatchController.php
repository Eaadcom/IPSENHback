<?php

namespace App\Http\Controllers;

use App\services\LikeMatchService;
use Illuminate\Http\Request;

class LikeMatchController extends Controller
{

    /**
     * @var LikeMatchService
     */
    private $likeMatchService;

    public function __construct(LikeMatchService $likeMatchService)
    {
        $this->likeMatchService = $likeMatchService;
    }

    public function delete(Request $request, $id)
    {
        $this->likeMatchService->delete($id);
    }
}
