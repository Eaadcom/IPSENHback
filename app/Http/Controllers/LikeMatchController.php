<?php

namespace App\Http\Controllers;

use App\services\LikeMatchService;
use Illuminate\Http\JsonResponse;

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

    public function delete($id): JsonResponse
    {
        $response = $this->likeMatchService->delete($id);
        return response()->json(['message' => $response]);
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
