<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\guard\JwtGuard;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\services\AuthService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Contracts\JWTSubject;


class AuthController extends Controller
{

    /**
     * @var AuthService
     */
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function authUser(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(
        /** @var JwtGuard auth() */
            auth()->refresh()
        );
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'token' => $token,
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        /** @var JWTSubject $user */
        $user = $this->authService->register(
            new User,
            $request->validated()
        );

        $token = auth()->login($user);

        return response()->json(['token' => $token]);
    }
}
