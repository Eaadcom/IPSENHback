<?php

namespace App\Http\Controllers;

use App\services\LikeMatchService;
use Illuminate\Http\JsonResponse;
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
