<?php

Route::get('/', 'HomeController@index');

Route::get('/user/{user?}', 'HomeController@index')
    ->where('user', '[a-zA-Z0-9_\-]+');

Route::get('/achievements', 'HomeController@index');


Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
    // Users
    Route::get('users.json', 'UsersController@index');
    Route::get('users/top.json', 'UsersController@getUsersTop');
    Route::get('user/{gitterId}.json', 'UsersController@getUser');

    // Achievements
    Route::get('achievements.json', 'AchievementsController@index');


    Route::any('/{any}', function () {
        return ['error' => 'Not found'];
    })->where('any', '.*?');
});


Route::any('/{any}', function () {
    return Redirect::to('/');
})->where('any', '.*?');