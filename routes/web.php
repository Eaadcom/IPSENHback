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

$router->group(['prefix' => 'auth'], function () use ($router) {

    // auth/login
    $router->post('login', 'AuthController@login');

    // auth/register
    $router->post('register', 'AuthController@register');

});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->group(['prefix' => 'v1'], function () use ($router) {

        // api/v1/users
        $router->get('/user', 'UserController@get');
        $router->post('/user', 'UserController@post');
        $router->put('/user/{id}', 'UserController@put');
        $router->delete('/user/{id}', 'UserController@delete');

    });
});
