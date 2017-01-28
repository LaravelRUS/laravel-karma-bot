<?php declare(strict_types=1);
/** @var Router $router */
use Illuminate\Http\Request;
use Illuminate\Routing\Router;


$router->middleware('auth:api')
    ->get('/user', function (Request $request) {
        return $request->user();
    });
