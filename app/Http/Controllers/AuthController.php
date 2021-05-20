<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Psy\Util\Json;
use Tymon\JWTAuth\JWTGuard;

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

        $credentials = $request->only(['email', 'password']);

        // verbetern leesbaarheid
        if (is_null($user = $this->authService->attempt($credentials))) {
            return response()->json(['message' => 'authentication failed'], 422);
        }

        $apiToken = base64_encode(Str::random(40));
        $user->api_token = $apiToken;
        $user->save();


        return response()->json([
            'api_token' => $apiToken,
            'user' => $user
        ]);
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

        try {

            $user = $this->authService->register($request->all());
            $user->update(['api_token' => $api_token = base64_encode(Str::random(40))]);

            return response()->json([
                'user' => $user,
                'api_token' => $api_token
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'failed to register user'], 422);
        }
    }
}
