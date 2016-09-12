<?php

Route::get('/', 'HomeController@index')->name('home');

Route::get('/user/{user?}', 'HomeController@index')->name('user')
    ->where('user', '[a-zA-Z0-9_\-]+');

Route::get('/achievements', 'HomeController@index')->name('achievements');

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
    // Users
    Route::get('users.json', 'UsersController@index')->name('api.users');
    Route::get('users/top.json', 'UsersController@top')->name('api.top');
    Route::get('user/search.json', 'SearchController@users')->name('api.users.search');
    Route::get('user/{gitterId}.json', 'UsersController@user')->name('api.user');

    // Achievements
    Route::get('achievements.json', 'AchievementsController@index')->name('api.achievements');
    Route::get('achieve/{name}/users.json', 'AchievementsController@users')->name('api.achieve.users')
        ->where('name', '[0-9]+');


    Route::any('/{any}', function () {
        return ['error' => 'Not found'];
    })->where('any', '.*?');
});


Route::any('/{any}', function () {
    return Redirect::to('/');
})->where('any', '.*?');