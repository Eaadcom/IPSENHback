<?php


namespace App\services;


use App\Models\User;

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

}
