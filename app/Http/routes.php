<?php
Route::get('/', 'HomeController@index');
Route::get('/user/{user?}', 'HomeController@index')
    ->where('user', '[a-zA-Z0-9_\-]+');

Route::group(['prefix' => 'api'], function() {
    Route::get('users.json', 'ApiController@getUsers');

    Route::get('user/{gitterId}.json', 'ApiController@getUser');
});