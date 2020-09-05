<?php

namespace App\Http\Requests\Playlist;

use App\Rules\SortablePlaylistVideos;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SortVideoInPlaylistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('sortVideos', $this->playlist);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'videos' => ['required', new SortablePlaylistVideos($this->playlist)]
        ];
    }
}
