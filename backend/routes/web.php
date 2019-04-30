<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Admin\SiteController@index');

// Admin
Route::group(['namespace' => 'Admin', 'prefix' => 'admin.cms'], function () {
    Auth::routes();
    Route::post('/upload', 'SiteController@upload')->name('admin.upload');

    Route::match(['get'], '/', 'SiteController@index')->name('admin.home');
    Route::match(['get', 'post'], '/card-group', 'CardGroupController@index')->name('admin.card.group.index');
    Route::match(['get', 'post'], '/card-group/save', 'CardGroupController@save')->name('admin.card.group.save');

    Route::match(['get', 'post'], '/card', 'CardController@index')->name('admin.card.index');
    Route::match(['get', 'post'], '/card/save', 'CardController@save')->name('admin.card.save');

    Route::match(['get', 'post'], '/store', 'StoreController@index')->name('admin.store.index');
    Route::match(['get', 'post'], '/store/save', 'StoreController@save')->name('admin.store.save');

    Route::match(['get', 'post'], '/member', 'MembersController@index')->name('admin.member.index');
    Route::match(['get'], '/member/{action?}/{id?}', 'MembersController@action')->name('admin.member.action');
});
