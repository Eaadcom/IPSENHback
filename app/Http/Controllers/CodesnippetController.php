<?php

namespace App\Http\Controllers;

use App\services\CodesnippetService;
use Exception;
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

    public function getByUserId($userId)
    {
        try {
            $codesnippets = $this->codesnippetService->getByUserId($userId);
            $response = response()->json($codesnippets);
        }
        catch (Exception $exception) {
            $response = response()->json(['message' => 'Could not collect codesnippets by user ID.']);
        }
        return $response;
    }

    public function getByAuthId()
    {
        $userId = auth()->id();
        try {
            $codesnippets = $this->codesnippetService->getByUserId($userId);
            $response = response()->json($codesnippets);
        }
        catch (Exception $exception) {
            $response = response()->json(['message' => 'Could not collect codesnippets by auth ID.']);
        }
        return $response;
    }


    public function post(Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
            'language' => 'required',
            'theme' => 'required',
        ]);
        try {
            $this->codesnippetService->create($request->all());
            $response = response()->json(['message' => 'Codesnippet succesfully created.']);
        }
        catch (Exception $exception) {
            $response = response()->json(['message' => 'Could not create codesnippet.']);
        }
        return $response;
    }

    public function put(Request $request, $id)
    {
        $this->validate($request, [
            'content' => 'required',
            'language' => 'required',
            'theme' => 'required',
        ]);
        try {
            $this->codesnippetService->update($id, $request->all());
            $response = response()->json(['message' => 'Codesnippet succesfully updated.']);
        }
        catch (Exception $exception) {
            $response = response()->json(['message' => 'Could not update codesnippet.']);
        }
        return $response;
    }

    public function delete($id)
    {
        try {
            $this->codesnippetService->delete($id);
            $response = response()->json(['message' => 'Codesnippet succesfully deleted.']);
        }
        catch(Exception $exception) {
            $response = response()->json(['message' => 'Could not delete codesnippet.']);
        }
        return $response;
    }

}
