<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterNewUserRequest;
use App\Http\Requests\Auth\RegisterVerifyUserRequest;
use App\Http\Requests\Auth\ResendVerificationCodeRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    /**
     * ثبت نام کاربر
     *
     * @param RegisterNewUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(RegisterNewUserRequest $request)
    {
        return UserService::registerNewUser($request);
    }

    /**
     * تایید کد فعالسازی کاربر
     *
     * @param RegisterVerifyUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function registerVerify(RegisterVerifyUserRequest $request)
    {
        return UserService::registerNewUserVerify($request);
    }

    /**
     * ارسال مجدد کد فعالسازی به کاربر
     *
     * @param ResendVerificationCodeRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function resendVerificationCode(ResendVerificationCodeRequest $request)
    {
        return UserService::resendVerificationCodeToUser($request);
    }
}
