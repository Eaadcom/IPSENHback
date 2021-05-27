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
        $result = $this->likeMatchService->delete($id);
        return response()->json(['message' => $result]);
    }

    public function get($id): JsonResponse
    {
        $result = $this->likeMatchService->getById($id);
        return response()->json($result);
    }

    public function getAll(): JsonResponse
    {
        $result = $this->likeMatchService->getAllLikeMatchesOfAuthUser();
        return response()->json($result);
    }
}
