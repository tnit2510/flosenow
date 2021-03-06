<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Api', 'prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login')->name('users.login');
    Route::post('register', 'AuthController@register')->name('users.register');
    Route::post('me', 'AuthController@me')->name('users.me');
    Route::post('logout', 'AuthController@logout')->name('users.logout');
    Route::post('refresh', 'AuthController@refresh')->name('users.refresh');
    Route::get('redirect/{provider}', 'SocialiteController@redirect');
    Route::get('callback/{provider}', 'SocialiteController@callback');
});

Route::group(['namespace' => 'Api'], function () {
    Route::get('home', 'HomeController')->name('home');
    Route::apiResource('announcements', 'AnnouncementController');
    Route::apiResource('bookmarks', 'BookmarkController');
    Route::apiResource('groups', 'GroupController');
    Route::apiResource('hashtags', 'HashtagController');
    Route::apiResource('pages', 'PageController');
        Route::get('pages/{page}/posts', 'PageController@listPosts')->name('pages.list_posts');
        Route::get('pages/{page}/post/{post}', 'PageController@post')->name('pages.post');
        Route::post('pages/{page}/create', 'PageController@createPost')->name('pages.create_post');
        Route::put('pages/{page}/post/{post}/edit', 'PageController@editPost')->name('pages.edit_post');
        Route::delete('pages/{page}/post/{post}/delete', 'PageController@deletePost')->name('pages.delete_post');
    Route::apiResource('posts', 'PostController');
        Route::post('posts/{post}/bookmark/{bookmark}', 'PostController@bookmark')->name('posts.bookmark');
        Route::delete('posts/{post}/bookmark/{bookmark}/delete', 'PostController@bookmarkDeletePost')->name('posts.bookmark_delete_post');
    Route::apiResource('titles', 'TitleController');
    Route::apiResource('users', 'UserController');
});
