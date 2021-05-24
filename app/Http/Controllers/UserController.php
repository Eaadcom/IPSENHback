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

    public function get(Request $request)
    {
        return User::first();
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

    public function put(Request $request, $id)
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

        $this->userService->update(User::wherefind($id), $request->all());
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
