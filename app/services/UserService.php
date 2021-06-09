<?php


namespace App\services;

use App\Models\Codesnippet;
use App\Models\Like;
use App\Models\User;
use Carbon\Carbon;

class UserService
{

    public function create(array $data)
    {
        $this->save(
            new User,
            $data
        );
    }

    public function update(User $user, array $data)
    {
        $this->save(
            $user,
            $data
        );
    }

    public function get($id){
        return User::select('id', 'first_name', 'middle_name', 'last_name', 'about_me')
            ->findOrFail($id);
    }

    public function save(User $user, array $data)
    {
        $user->fill($data);

        $user->save();
    }

    public function delete($id)
    {
        User::find($id)->delete();
    }

    public function getPotentialMatches(int $id): array
    {

        $user = User::findOrFail($id);

        $maxAge = Carbon::createFromFormat('Y-m-d', $user['date_of_birth'])
            ->addYears($user['age_range_top'])->year;
        $minAge = Carbon::createFromFormat('Y-m-d', $user['date_of_birth'])
            ->subYears($user['age_range_bottom'])->year;

        $likedUsers = Like::select('user_id_of_liked_user')
            ->where('user_id', '=', $id)
            ->get()->keyBy('user_id_of_liked_user')->keys()->all();

        $likedBackUsers = Like::select('user_id')
            ->where('user_id_of_liked_user', '=', $id)
            ->where('liked_back_type', '!=', null)
            ->get()->keyBy('user_id')->keys()->all();

        $usersWithCodesnippets = Codesnippet::select('user_id');

        // This query only selects users based on gender, year
        // , whether they have been liked yet or not and
        // wherether the user has a codesnippet created,
        // it does not factor in the exact birthdate within the year
        return User::select('id')
            ->where('id', '!=', $id)
            ->whereNotIn('id', $likedUsers)
            ->whereNotIn('id', $likedBackUsers)
            ->whereIn('id', $usersWithCodesnippets)
            ->where('gender', '=', $user['interest'])
            ->whereYear('date_of_birth', '<', $maxAge)
            ->whereYear('date_of_birth', '>', $minAge)
            ->get()->keyBy('id')->forget($id)->keys()->all();
    }
}
