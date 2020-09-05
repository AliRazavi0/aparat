<?php

namespace App\Http\Requests\Category;

use App\Rules\UniqueForUser;
use App\Rules\UploadedCategoryBannerId;
use Illuminate\Foundation\Http\FormRequest;

class EditCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:100', new UniqueForUser('categories')],
        ];
    }
}
