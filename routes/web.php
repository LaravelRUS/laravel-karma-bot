<?php declare(strict_types=1);
/** @var Router $router */
use Illuminate\Http\Request;
use Illuminate\Routing\Router;


$router->get('/', 'HomeController@index');
