<?php

namespace App\Http\Requests\Video;

use App\Rules\CanChangeVideoState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ShowVideoStatisticsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('showStatistics', $this->video);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'last_n_days' => 'nullable|in:7,14,30,90'
        ];
    }
}
