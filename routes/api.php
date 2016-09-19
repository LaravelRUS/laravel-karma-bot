<?php
/** @var \Illuminate\Routing\Router $router */

// Users
$router->get('users.json', 'UsersController@index')->name('api.users');
$router->get('users/top.json', 'UsersController@top')->name('api.top');
$router->get('user/search.json', 'SearchController@users')->name('api.users.search');
$router->get('user/{gitterId}.json', 'UsersController@user')->name('api.user');

// Achievements
$router->get('achievements.json', 'AchievementsController@index')->name('api.achievements');
$router->get('achieve/{name}/users.json', 'AchievementsController@users')->name('api.achieve.users')
    ->where('name', '[0-9]+');


$router->any('/{any}', function () { return ['error' => 'Not found']; })->where('any', '.*?');



