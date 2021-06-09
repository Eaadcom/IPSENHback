<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function get($id)
    {
        return $this->userService->get($id);
    }

    public function post(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'date_of_birth' => 'required',
            'about_me' => 'required',
            'age_range_bottom' => 'required',
            'age_range_top' => 'required',
            'max_distance' => 'required',
            'interest' => 'required',
        ]);

        $this->userService->create($request->all());

    }

    public function put(Request $request)
    {
        $authUserId = auth()->id();
        $this->validate($request, [
            'email' => "required|email|unique:users,email," . $authUserId,
            'password' => 'string|min:8',
            'first_name' => 'string|min:3',
            'middle_name' => 'string|min:3',
            'last_name' => 'string|min:3',
            'date_of_birth' => 'date',
            'about_me' => 'nullable|string',
            'age_range_bottom' => 'integer',
            'age_range_top' => 'integer',
            'max_distance' => 'integer',
            'interest' => 'in:male,female',
        ]);

        $this->userService->update(auth()->user(), $request->all());
        return response()->json(['message' => 'user Updated']);
    }

    public function delete(Request $request, $id)
    {
        $this->userService->delete($id);
    }

    public function getPotentialMatches($id): array
    {

        return $this->userService->getPotentialMatches($id);

    }
}
