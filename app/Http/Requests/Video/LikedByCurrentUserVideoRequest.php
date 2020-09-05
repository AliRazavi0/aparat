<?php

namespace App\Http\Requests\Video;

use App\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class LikedByCurrentUserVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('seeLikedList', Video::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
