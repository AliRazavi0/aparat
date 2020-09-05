<?php

namespace App\Http\Requests\Video;

use App\Rules\CategoryId;
use App\Rules\OwnPlaylistId;
use App\Rules\UploadedVideoBannerId;
use App\Rules\UploadedVideoId;
use Illuminate\Foundation\Http\FormRequest;

class CreateVideoRequest extends FormRequest
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
            'video_id' => ['required', new UploadedVideoId()],
            'title' => 'required|string|max:255',
            'category' => ['required', new CategoryId(CategoryId::PUBLIC_CATEGORIES)],
            'info' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'playlist' => ['nullable', new OwnPlaylistId()],
            'channel_category' => ['nullable', new CategoryId(CategoryId::PRIVATE_CATEGORIES)],
            'banner' => [new UploadedVideoBannerId()],
            'publish_at' => 'nullable|date_format:Y-m-d H:i:s|after:now',
            'enable_comments' => 'required|boolean',
            'enable_watermark' => 'required|boolean',
        ];
    }
}
