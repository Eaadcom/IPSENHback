<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\services\UserService;
use Illuminate\Http\Request;

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

    public function put(UpdateUserRequest $request)
    {
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
