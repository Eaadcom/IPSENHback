<?php


namespace App\services;


use App\Models\Codesnippet;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Collection\Collection;

class CodesnippetService
{

    public function update($id, array $data)
    {
        $codesnippet = $this->getAuthUserCodesnippet($id);
        $this->save($codesnippet, $data);
    }

    public function create(array $data): int
    {
        return $this->save(new Codesnippet, $data);
    }

    private function save(Codesnippet $codesnippet, array $data): int
    {
        $codesnippet->fill($data);
        $codesnippet->user()->associate(auth()->user());
        $codesnippet->save();
        return $codesnippet->id;
    }

    public function delete($id)
    {
        $this->getAuthUserCodesnippet($id)->delete();
    }

    public function getByUserId($id)
    {
        return User::findOrFail($id)->codesnippets()->orderByDesc('created_at')->get();
    }

    public function getAuthUserCodesnippet(int $codesnippetId)
    {
        $user = auth()->user();

        return $user->codesnippets()->orderByDesc('created_at')->findOrFail($codesnippetId);
    }
}
