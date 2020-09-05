<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\InfoRequest;
use App\Http\Requests\Channel\StatisticsRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UpdateUserInfoConfirmRequest;
use App\Http\Requests\Channel\UpdateUserInfoRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Services\ChannelService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ChannelController extends Controller
{
    public function update(UpdateChannelRequest $request)
    {
        return ChannelService::updateChannelInfo($request);
    }

    public function uploadBanner(UploadBannerForChannelRequest $request)
    {
        return ChannelService::uploadAvatarForChannel($request);
    }

    public function updateSocials(UpdateSocialsRequest $request)
    {
        return ChannelService::updateSocials($request);
    }

    public function updateUserInfo(UpdateUserInfoRequest $request)
    {
        return ChannelService::updateUserInfo($request);
    }

    public function updateUserInfoConfirm(UpdateUserInfoConfirmRequest $request)
    {
        return ChannelService::updateUserInfoConfirm($request);
    }

    public function statistics(StatisticsRequest $request)
    {
        return ChannelService::statistics($request);
    }

    public function info(InfoRequest $request)
    {
        return ChannelService::info($request);
    }
}
