<?php

namespace App\Http\Requests;

use App\Models\Like;
use Pearl\RequestValidate\RequestAbstract;

class StoreMessageRequest extends RequestAbstract
{

    protected function authorize(): bool
    {
        return isset(request()->id) && $this->userIsInLikeMatch();
    }

    protected function prepareForValidation()
    {
        $this->request->add(['like_match_id' => request()->id]);
    }

    /**
     * Validates the post request for creating a message.
     * Includes validation of existing attributes, exists in database, auth user exists in the like
     * And if the like_match_id is in the like
     */
    public function rules(): array
    {

        return [
            'content' => 'required',
            'like_match_id' => 'required|exists:like_matches,id',
        ];
    }

    private function userIsInLikeMatch(): bool
    {
        return Like::query()->where(function ($query) {
            $query->where('user_id', auth()->id())->orWhere('user_id_of_liked_user', auth()->id());
        })->where('like_match_id', request()->id)->exists();
    }
}
