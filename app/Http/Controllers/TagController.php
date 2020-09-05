<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\ListTagRequest;
use App\Services\TagService;

class TagController extends Controller
{
    public function index(ListTagRequest $request)
    {
        return TagService::index($request);
    }

    public function create(CreateTagRequest $request)
    {
        return TagService::create($request);
    }
}
