<?php


namespace App\services;


use App\Models\Codesnippet;
use App\Models\User;

class CodesnippetService
{


    public function createOrUpdate(Codesnippet $codesnippet, array $data)
    {
        $codesnippet->fill($data);

        $user = User::find(/*auth()->id()*/2);

        $user->codesnippets()->save($codesnippet);
    }

    public function delete($codesnippet)
    {
        $codesnippet->delete();
    }

    public function getByUserId($id)
    {
        return User::find($id)->codesnippets;
    }
}
