<?php

namespace App\Http\Requests\User;

use App\Rules\MobileRule;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\Unique;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile' => [new MobileRule, (new Unique('users'))->ignore($this->user->id)],
            'email' => ['email', (new Unique('users'))->ignore($this->user->id)],
            'name' => "string",
            'type' => [new In(User::TYPES)],
            'website' => "url",
        ];
    }
}
