<?php


namespace App\services;


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

        $user = User::find($id);

        $maxAge = Carbon::createFromFormat('Y-m-d', $user['date_of_birth'])
            ->addYears($user['age_range_top'])->year;
        $minAge = Carbon::createFromFormat('Y-m-d', $user['date_of_birth'])
            ->subYears($user['age_range_bottom'])->year;

        // This query only selects users based on gender and year,
        // it does not factor in the exact birthdate within the year
        return User::select('id')
            ->where('gender', '=', $user['interest'])
            ->whereYear('date_of_birth', '<', $maxAge)
            ->whereYear('date_of_birth', '>', $minAge)
            ->get()->keyBy('id')->forget($id)->keys()->all();

    }

}
