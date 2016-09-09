<?php

Route::get('/', 'HomeController@index')
    ->name('home');

Route::get('/user/{user?}', 'HomeController@index')
    ->where('user', '[a-zA-Z0-9_\-]+')
    ->name('user');

Route::get('/achievements', 'HomeController@index')
    ->name('achievements');


Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
    // Users
    Route::get('users.json', 'UsersController@index')->name('api.users');
    Route::get('users/top.json', 'UsersController@getUsersTop')->name('api.top');
    Route::get('user/{gitterId}.json', 'UsersController@getUser')->name('api.user');
    Route::get('user/search.json', 'SearchController@users')->name('api.users.search');

    // Achievements
    Route::get('achievements.json', 'AchievementsController@index')->name('api.achievements');


    Route::any('/{any}', function () {
        return ['error' => 'Not found'];
    })->where('any', '.*?');
});


Route::any('/{any}', function () {
    return Redirect::to('/');
})->where('any', '.*?');