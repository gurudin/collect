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
    
    Route::match(['get', 'post'], '/spider', 'SpiderController@spider')->name('admin.spider');
    Route::match(['get', 'post'], '/spider-save', 'SpiderController@spiderSave')->name('admin.spider.save');
});
