<?php

namespace App\Services;


use App\Channel;
use App\Http\Requests\Channel\InfoRequest;
use App\Http\Requests\Channel\StatisticsRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UpdateUserInfoConfirmRequest;
use App\Http\Requests\Channel\UpdateUserInfoRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Mail\ConfirmationCodeMail;
use App\Video;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

const USER_CONFIRMATION_CACHE_KEY = 'user-confirmation-code';

class ChannelService extends BaseService
{
    public static function updateChannelInfo(UpdateChannelRequest $request)
    {
        try {
            if ($channelId = $request->route('id')) {
                $channel = Channel::findOrFail($channelId);
                $user = $channel->user;
            } else {
                $user = auth()->user();
                $channel = $user->channel;
            }

            DB::beginTransaction();

            $channel->name = $request->name;
            $channel->info = $request->info;
            $channel->save();

            $user->website = $request->website;
            $user->save();

            DB::commit();
            return response(['message' => 'ثبت تغییرات کانال انجام شد'], 200);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }

    public static function uploadAvatarForChannel(UploadBannerForChannelRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $fileName = md5(auth()->id()) . '-' . Str::random(15);
            Storage::disk('channel')->put($fileName, $banner->get());

            $channel = auth()->user()->channel;
            if ($channel->banner) {
                Storage::disk('channel')->delete($channel->banner);
            }
            $channel->banner = Storage::disk('channel')->url($fileName);
            $channel->save();

            return response([
                'banner' => Storage::disk('channel')->url($fileName)
            ], 200);
        } catch (\Exception $e) {
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }

    public static function updateSocials(UpdateSocialsRequest $request)
    {
        try {
            $socials = [
                'cloob' => $request->input('cloob'),
                'lenzor' => $request->input('lenzor'),
                'facebook' => $request->input('facebook'),
                'twitter' => $request->input('twitter'),
                'telegram' => $request->input('telegram'),
            ];

            auth()->user()->channel->update(['socials' => $socials]);
            return response(['message' => 'با موفقیت ثبت شد'], 200);
        } catch (Exception $exception) {
            Log::error($exception);
            return response(['message' => 'خطایی رخ داده است'], 500);
        }
    }

    public static function statistics(StatisticsRequest $request)
    {
        $topVideos = $request->user()
            ->channelVideos()
            ->select([
                'videos.id', 'videos.slug', 'videos.title', 'videos.duration',
                DB::raw('count(video_views.id) as views'),
            ])
            ->leftJoin('video_views', 'videos.id', 'video_views.video_id')
            ->groupBy('videos.id')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $fromDate = now()->subDays(
            $request->get('last_n_days', 7)
        )->toDateString();

        $data = [
            'views' => [],
            'total_views' => 0,
            'top_videos' => $topVideos,
            'total_followers' => $request->user()->followers()->count(),
            'total_videos' => $request->user()->channelVideos()->count(),
            'total_comments' => Video::channelComments($request->user()->id)
                ->selectRaw('comments.*')
                ->count(), //TODO تعداد نظرات تایید نشده رو باید بگیریم
        ];

        Video::views($request->user()->id)
            ->whereRaw("date(video_views.created_at) >= '{$fromDate}'")
            ->selectRaw('date(video_views.created_at) as date, count(*) as views')
            ->groupBy(DB::raw('date(video_views.created_at)'))
            ->get()
            ->each(function ($item) use (&$data) {
                $data['total_views'] += $item->views;
                $data['views'][$item->date] = $item->views;
            });

        return $data;
    }

    public static function info(InfoRequest $request)
    {
        $videos = $request->channel->user
            ->channelVideos()
            ->with(['playlist'])
            ->where('state', Video::STATE_ACCEPTED)
            ->get();

        $playlists = [];
        foreach ($videos as $video) {
            if ($video->playlist) {
                if (empty($playlists[$video->playlist[0]->id])) {
                    $playlists[$video->playlist[0]->id] = Arr::only($video->playlist[0]->toArray(), ['id', 'title', 'created_at']);
                    $playlists[$video->playlist[0]->id]['size'] = 1;
                    $playlists[$video->playlist[0]->id]['video'] = Arr::only($video->toArray(), ['id', 'slug', 'title', 'banner_link']);
                } else {
                    $playlists[$video->playlist[0]->id]['size']++;
                }
            }
        }

        $isFollowed = false;
        if ($currentUser = $request->user('api')) {
            $isFollowed = (bool)$currentUser->followings()->where('user_id2', $request->channel->user->id)->count();
        }
        return [
            'channel' => [
                'name' => $request->channel->name,
                'banner' => $request->channel->banner,
                'info' => $request->channel->info,
                'created_at' => $request->channel->created_at,
                'videos_count' => count($videos),
                'views_count' => $request->channel->user->views()->count(),
                'is_followed' => $isFollowed,
            ],
            'user' => [
                'avatar' => $request->channel->user->avatar,
                'playlists' => array_values($playlists)
            ],
            'videos' => $videos,
        ];
    }

    public static function updateUserInfo(UpdateUserInfoRequest $request)
    {
        try {
            $key = USER_CONFIRMATION_CACHE_KEY . '-' . $request->user()->id;
            $data = [
                'code' => random_int(1000, 9999),
                'field' => $request->getFieldName(),
                'value' => $request->getFieldValue()
            ];

            Cache::put($key, $data, now()->addDay()->getTimestamp());

            if ($request->getFieldName() === 'email') {
                Mail::to($request->user())->send(new ConfirmationCodeMail($data['code'], $data['value']));
            }
            else{
                \Kavenegar::Send(config('kavenegar.sender'), $data['value'], 'کد تایید ' . $data['code']);
            }

            Log::info('confirmation code', $data);

            return Response::create(['message' => 'کد تاییدی برای شما ارسال شد'], Response::HTTP_OK);

        } catch (\Exception $exception) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'عملیات مقدور نمیباشد لطفا مجددا تلاش کنید');
        }
    }

    public static function updateUserInfoConfirm(UpdateUserInfoConfirmRequest $request)
    {
        try {
            $key = USER_CONFIRMATION_CACHE_KEY . '-' . $request->user()->id;
            $data = Cache::get($key);

            if (!empty($data) && $data['code'] == $request->code) {
                $request->user()->{$data['field']} = $data['value'];
                $request->user()->save();

                return response([$data['field'] => $data['value']]);
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response(['message' => 'خطایی رخ داده است'], 500);
    }
}
