<?php

namespace App\Http\Controllers;

use App\Models\Codesnippet;
use App\Models\User;
use App\services\CodesnippetService;
use Illuminate\Http\Request;

class CodesnippetController extends Controller
{
    /**
     * @var CodesnippetService
     */
    private $codesnippetService;

    public function __construct(CodesnippetService $codesnippetService)
    {
        $this->codesnippetService = $codesnippetService;
    }


    public function getByUserId($id)
    {
        return $this->codesnippetService->getByUserId($id);
    }

    public function post(Request $request)
    {

        $this->validate($request, [
            'content' => 'required',
            'language' => 'required',
            'theme' => 'required',
        ]);

        $this->codesnippetService->createOrUpdate(new Codesnippet, $request->all());

    }

    public function put(Request $request, $id)
    {

        $this->validate($request, [
            'content' => 'required',
            'language' => 'required',
            'theme' => 'required',
        ]);

        $codesnippet = $this->getAuthUserCodesnippet($id);

        $this->codesnippetService->createOrUpdate($codesnippet, $request->all());
    }

    public function delete($id)
    {
        $codesnippet = $this->getAuthUserCodesnippet($id);
        $this->codesnippetService->delete($codesnippet);
    }

    private function getAuthUserCodesnippet(int $codesnippetId) {
        $user = User::find(/*auth()->id()*/2);

        return $user->codesnippets()->find($codesnippetId);
    }

}
