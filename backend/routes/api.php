<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function () {
    Route::get('/cards', 'CardController@cards')->name('api.cards');

    Route::get('/member/cards', 'MemberController@cards')->name('api.member.cards');
    Route::post('/member/destruct', 'MemberController@destruct')->name('api.member.destruct');
});
