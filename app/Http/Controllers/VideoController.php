<?php

namespace App\Http\Controllers;

use App\Http\Requests\Video\CategorizedVideosRequest;
use App\Http\Requests\Video\ChangeStateVideoRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\DeleteVideoRequest;
use App\Http\Requests\Video\FavouritesVideoListRequest;
use App\Http\Requests\Video\LikedByCurrentUserVideoRequest;
use App\Http\Requests\Video\LikeVideoRequest;
use App\Http\Requests\Video\ListVideoRequest;
use App\Http\Requests\Video\RepublishVideoRequest;
use App\Http\Requests\Video\ShowVideoCommentsRequest;
use App\Http\Requests\Video\ShowVideoRequest;
use App\Http\Requests\Video\ShowVideoStatisticsRequest;
use App\Http\Requests\Video\UnlikeVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Http\Requests\Video\VideoCommentsRequest;
use App\Services\CommentService;
use App\Services\VideoService;

class VideoController extends Controller
{
    public function list(ListVideoRequest $request)
    {
        return VideoService::list($request);
    }

    public function show(ShowVideoRequest $request)
    {
        return VideoService::show($request);
    }

    public function upload(UploadVideoRequest $request)
    {
        return VideoService::upload($request);
    }

    public function uploadBanner(UploadVideoBannerRequest $request)
    {
        return VideoService::uploadBanner($request);
    }

    public function create(CreateVideoRequest $request)
    {
        return VideoService::create($request);
    }

    public function update(UpdateVideoRequest $request)
    {
        return VideoService::update($request);
    }

    public function republish(RepublishVideoRequest $request)
    {
        return VideoService::republish($request);
    }

    public function like(LikeVideoRequest $request)
    {
        return VideoService::like($request);
    }

    public function unlike(UnlikeVideoRequest $request)
    {
        return VideoService::unlike($request);
    }

    public function likedByCurrentUser(LikedByCurrentUserVideoRequest $request)
    {
        return VideoService::likedByCurrentUser($request);
    }

    public function changeState(ChangeStateVideoRequest $request)
    {
        return VideoService::changeState($request);
    }

    public function delete(DeleteVideoRequest $request)
    {
        return VideoService::delete($request);
    }

    public function statistics(ShowVideoStatisticsRequest $request)
    {
        return VideoService::statistics($request);
    }

    public function favourites(FavouritesVideoListRequest $request)
    {
        return VideoService::favourites($request);
    }

    public function categorizedVideos(CategorizedVideosRequest $request)
    {
        return VideoService::categorizedVideos($request);
    }

    public function comments(VideoCommentsRequest $request)
    {
        return CommentService::forVideo($request->video);
    }
}
