<?php

namespace App\Http\Requests\Video;

use Illuminate\Foundation\Http\FormRequest;

class ShowVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //TODO سطح دسترسی دیدن ویدیو
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
