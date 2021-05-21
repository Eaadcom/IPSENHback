<?php

namespace App\Http\Controllers;

use App\services\LikeMatchService;
use Illuminate\Http\JsonResponse;

class LikeMatchController extends Controller
{
    private $likeMatchService;

    public function __construct(LikeMatchService $likeMatchService)
    {
        $this->likeMatchService = $likeMatchService;
    }

    public function get($id): JsonResponse
    {
        $response = $this->likeMatchService->getById($id);
        return response()->json(['message' => $response]);

    }

    public function getAll(): JsonResponse
    {
        $response = $this->likeMatchService->getAllLikeMatchesOfAuthUser();
        return response()->json(['message' => $response]);
    }
}
