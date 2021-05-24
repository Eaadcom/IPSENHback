<?php


namespace App\services;


use App\Models\Codesnippet;
use App\Models\User;

class CodesnippetService
{

    public function update($id, array $data)
    {
        $codesnippet = $this->getAuthUserCodesnippet($id);
        $this->save($codesnippet, $data);
    }
    public function create(array $data){
        $this->save(new Codesnippet, $data);
    }

    private function save(Codesnippet $codesnippet, array $data){
        $codesnippet->fill($data);
        $codesnippet->user()->associate(auth()->user());
        $codesnippet->save();
    }

    public function delete($id)
    {
        $this->getAuthUserCodesnippet($id)->delete();
    }

    public function getByUserId($id)
    {
        return User::findOrFail($id)->codesnippets;
    }

    public function getAuthUserCodesnippet(int $codesnippetId) {
        $user = auth()->user();

        return $user->codesnippets()->findOrFail($codesnippetId);
    }
}
