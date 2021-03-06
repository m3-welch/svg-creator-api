<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/login', [
    'uses' => 'AccountController@login'
]);

$router->post('/signup', [
    'uses' => 'AccountController@signup'
]);

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/test', function () {
        return 'logged in';
    });
});
