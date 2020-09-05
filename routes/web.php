<?php

use App\Mail\ConfirmationCodeMail;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;
use App\User;

Route::get('/email-confirmation', [
    'uses' => function () {
        $email = new ConfirmationCodeMail(1234, 'ali@aparat.me');

        //Mail::to(User::first())->send($email);

        return $email;
    }
]);


Route::get('/email-verification', [
    'uses' => function () {
        $email = new VerificationCodeMail(1234);

        //Mail::to(User::first())->send($email);

        return $email;
    }
]);
