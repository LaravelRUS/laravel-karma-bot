<?php
/** @var \Illuminate\Routing\Router $router */


$router->get('/', 'HomeController@index')->name('home');

$router->get('/user/{user?}', 'HomeController@index')->name('user')
    ->where('user', '[a-zA-Z0-9_\-]+');

$router->get('/achievements', 'HomeController@index')->name('achievements');

$router->any('/{any}', function () {
    return Redirect::to('/');
})->where('any', '.*?');