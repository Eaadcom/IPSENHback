<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Pearl\RequestValidate\RequestAbstract;

class StoreMessageRequest extends RequestAbstract
{
    /**
     * Validates the post request for creating a message.
     * Includes validation of existing attributes, exists in database, auth user exists in the like
     * And if the like_match_id is in the like
     */
    public function rules(): array
    {
        $request = request();

        return [
            'content' => 'required',

            'like_match_id' => 'required|exists:like_matches,id',

            Rule::exists('likes')->where(function ($query) use ($request) {
                    return $query->where(function ($query) {
                        return $query->where('user_id', auth()->id())->orWhere('user_id_of_liked_user', auth()->id());
                    })->where('like_match_id', $request->get('like_match_id'));
            })
        ];
    }
}
