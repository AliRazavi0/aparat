<?php

/**
 *auth
 **/
Route::group([], function ($router) {
    $router->group(['namespace' => '\Laravel\Passport\Http\Controllers'], function ($router) {
        $router->post('login', [
            'as' => 'auth.login',
            'middleware' => ['throttle'],
            'uses' => 'AccessTokenController@issueToken',
        ]);
    });

    $router->post('register', [
        'as' => 'auth.register',
        'uses' => 'AuthController@register',
    ]);

    $router->post('register-verify', [
        'as' => 'auth.register.verify',
        'uses' => 'AuthController@registerVerify',
    ]);

    $router->post('resend-verification-code', [
        'as' => 'auth.register.resend.verification.code',
        'uses' => 'AuthController@resendVerificationCode',
    ]);

    $router->get('categorized-videos', [
        'as' => 'categorized-videos',
        'uses' => 'VideoController@categorizedVideos',
    ]);
});

/**
 *User
 */
Route::group(['middleware' => ['auth:api']], function ($router) {
    $router->post('change-email', [
        'as' => 'change.email',
        'uses' => 'UserController@changeEmail'
    ]);

    $router->post('change-email-submit', [
        'as' => 'change.email.submit',
        'uses' => 'UserController@changeEmailSubmit'
    ]);

    $router->match(['post', 'put'], 'change-password', [
        'as' => 'password.change',
        'uses' => 'UserController@changePassword'
    ]);

    $router->post('logout', [
        'as' => 'auth.logout',
        'uses' => 'UserController@logout',
    ]);

    Route::group(['prefix' => 'user'], function ($router) {
        $router->match(['post', 'get'], '/{channel}/follow', [
            'as' => 'user.follow',
            'uses' => 'Usercontroller@follow',
        ]);

        $router->match(['post', 'get'], '/{channel}/unfollow', [
            'as' => 'user.unfollow',
            'uses' => 'Usercontroller@unfollow',
        ]);

        $router->get('/followings', [
            'as' => 'user.followings',
            'uses' => 'UserController@followings'
        ]);

        $router->get('/followers', [
            'as' => 'user.followers',
            'uses' => 'UserController@followers'
        ]);

        $router->delete('/me', [
            'as' => 'user.unregister',
            'uses' => 'UserController@unregister'
        ]);

        $router->delete('/{user}', [
            'as' => 'user.delete',
            'uses' => 'UserController@delete'
        ]);

        $router->get('/me', [
            'as' => 'user.me',
            'uses' => 'UserController@me'
        ]);

        $router->get('/', [
            'as' => 'user.list',
            'uses' => 'UserController@list'
        ]);

        $router->put('/{user}', [
            'as' => 'user.update',
            'uses' => 'UserController@update'
        ]);

        $router->put('/{user}/reset-password', [
            'as' => 'user.reset-password',
            'uses' => 'UserController@resetPassword'
        ]);
    });
});

/**
 * channel
 */
Route::group(['prefix' => '/channel'], function ($router) {
    Route::group(['middleware' => ['auth:api']], function ($router) {
        $router->match(['post', 'put'], '/socials', [
            'as' => 'chanel.update.socials',
            'uses' => 'Channelcontroller@updateSocials',
        ]);

        $router->match(['post', 'put'], '/user-info', [
            'as' => 'chanel.update.user-info',
            'uses' => 'Channelcontroller@updateUserInfo',
        ]);

        $router->match(['post', 'put'], '/user-info-confirm', [
            'as' => 'chanel.update.user-info-confirm',
            'uses' => 'Channelcontroller@updateUserInfoConfirm',
        ]);

        $router->put('/{id?}', [
            'as' => 'channel.update',
            'uses' => 'ChannelController@update',
        ]);

        $router->match(['post', 'put'], '/', [
            'as' => 'channel.upload.banner',
            'uses' => 'ChannelController@uploadBanner',
        ]);

        $router->get('/statistics', [
            'as' => 'chanel.statistics',
            'uses' => 'Channelcontroller@statistics',
        ]);
    });

    $router->get('/{channel}', [
        'as' => 'chanel.info',
        'uses' => 'Channelcontroller@info',
    ]);
});

/**
 * video
 */
Route::group(['middleware' => [], 'prefix' => '/video'], function ($router) {
    $router->match(['get', 'post'], '/{video}/like', [
        'as' => 'video.like',
        'uses' => 'VideoController@like'
    ]);

    $router->match(['get', 'post'], '/{video}/unlike', [
        'as' => 'video.unlike',
        'uses' => 'VideoController@unlike'
    ]);

    $router->get('/', [
        'as' => 'video.list',
        'uses' => 'VideoController@list'
    ]);

    // user auth
    Route::group(['middleware' => ['auth:api']], function ($router) {
        $router->post('/upload', [
            'as' => 'video.upload',
            'uses' => 'VideoController@upload'
        ]);

        $router->post('/upload-banner', [
            'as' => 'video.upload.banner',
            'uses' => 'VideoController@uploadBanner'
        ]);

        $router->post('/', [
            'as' => 'video.create',
            'uses' => 'VideoController@create'
        ]);

        $router->post('/{video}/republish', [
            'as' => 'video.republish',
            'uses' => 'VideoController@republish'
        ]);

        $router->put('/{video}/state', [
            'as' => 'video.change.state',
            'uses' => 'VideoController@changeState'
        ]);

        $router->put('/{video}', [
            'as' => 'video.update',
            'uses' => 'VideoController@update'
        ]);

        $router->get('/liked', [
            'as' => 'video.liked',
            'uses' => 'VideoController@likedByCurrentUser'
        ]);

        $router->get('/{video}/statistics', [
            'as' => 'video.statistics',
            'uses' => 'VideoController@statistics'
        ]);

        $router->get('/favourites', [
            'as' => 'video.favourites',
            'uses' => 'VideoController@favourites'
        ]);

        $router->delete('/{video}', [
            'as' => 'video.delete',
            'uses' => 'VideoController@delete'
        ]);
    });

    $router->get('/{video}', [
        'as' => 'video.show',
        'uses' => 'VideoController@show'
    ]);

    $router->get('/{video}/comments', [
        'as' => 'video.comments',
        'uses' => 'VideoController@comments'
    ]);
});

/**
 * categories
 */
Route::group(['middleware' => ['auth:api'], 'prefix' => '/category'], function ($router) {
    $router->get('/', [
        'as' => 'category.all',
        'uses' => 'CategoryController@index'
    ]);

    $router->get('/my', [
        'as' => 'category.my',
        'uses' => 'CategoryController@my'
    ]);

    $router->post('/upload-banner', [
        'as' => 'category.upload.banner',
        'uses' => 'CategoryController@uploadBanner'
    ]);

    $router->post('/', [
        'as' => 'category.create',
        'uses' => 'CategoryController@create'
    ]);

    $router->put('/{category}', [
        'as' => 'category.edit',
        'uses' => 'CategoryController@edit'
    ]);
});

/**
 *playlist
 */
Route::group(['middleware' => [], 'prefix' => '/playlist'], function ($router) {
    Route::group(['middleware' => ['auth:api']], function ($router) {
        $router->get('/', [
            'as' => 'playlist.all',
            'uses' => 'PlaylistController@index'
        ]);

        $router->get('/my', [
            'as' => 'playlist.my',
            'uses' => 'PlaylistController@my'
        ]);

        $router->post('/', [
            'as' => 'playlist.create',
            'uses' => 'PlaylistController@create'
        ]);

        $router->match(['post', 'put'], '/{playlist}/sort', [
            'as' => 'playlist.sort',
            'uses' => 'PlaylistController@sortVideos'
        ]);

        $router->match(['post', 'put'], '/{playlist}/{video}', [
            'as' => 'playlist.add-video',
            'uses' => 'PlaylistController@addVideo'
        ]);
    });

    $router->get('/{playlist}', [
        'as' => 'playlist.show',
        'uses' => 'PlaylistController@show'
    ]);
});

/**
 * tags
 */
Route::group(['middleware' => ['auth:api'], 'prefix' => '/tag'], function ($router) {
    $router->get('/', [
        'as' => 'tag.all',
        'uses' => 'TagController@index'
    ]);

    $router->post('/', [
        'as' => 'tag.create',
        'uses' => 'TagController@create'
    ]);
});

/**
 * comment
 */
Route::group(['middleware' => ['auth:api'], 'prefix' => '/comment'], function ($router) {
    $router->get('/', [
        'as' => 'comment.all',
        'uses' => 'CommentController@index'
    ]);

    $router->post('/', [
        'as' => 'comment.create',
        'uses' => 'CommentController@create'
    ]);

    $router->match(['post', 'put'], '/{comment}/state', [
        'as' => 'comment.change.state',
        'uses' => 'CommentController@changeState'
    ]);

    $router->delete('/{comment}', [
        'as' => 'comment.delete',
        'uses' => 'CommentController@delete'
    ]);
});
