<?php

namespace App\Http\Controllers;

use App\services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;

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

    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        try {

            $credentials = $request->only(['email', 'password']);
            $user = $this->authService->login($credentials);

            return response()->json([
                'api_token' => $user->api_token,
                'user' => $user
            ]);

        } catch (UnauthorizedException $exception) {
            return response()->json(['message' => 'failed to authenticate user by credentials'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function register(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'first_name' => 'required|string|min:3',
            'middle_name' => 'string|min:3',
            'last_name' => 'required|string|min:3',
            'date_of_birth' => 'required|date',
            'about_me' => 'required|string',
            'age_range_bottom' => 'required|integer',
            'age_range_top' => 'required|integer',
            'max_distance' => 'required|integer',
            'interest' => 'required|string',
        ]);

        $user = $this->authService->register($request->all());

        return response()->json([
            'user' => $user,
            'api_token' => $user->api_token
        ]);
    }
}
