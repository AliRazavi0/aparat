<?php

namespace App\Http\Requests\Comment;

use App\Comment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\In;

class ChangeCommentStateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('changeState', [$this->comment, $this->state]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'state' => ['required', new In([Comment::STATE_ACCEPTED, Comment::STATE_READ])],
        ];
    }
}
