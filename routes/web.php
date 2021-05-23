<?php

/** @var Router $router */

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

use Laravel\Lumen\Routing\Router;

$router->group(['prefix' => 'auth'], function ($router) {

    // auth/login
    $router->post('login', 'AuthController@login');

    // auth/register
    $router->post('register', 'AuthController@register');


});

// protected routes
$router->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function ($router) {
    // api/v1/messages
    $router->post('message', 'MessageController@post');

    // api/v1/likeMatches
    $router->get('likematch', 'LikeMatchController@getAll');
    $router->get('likematch/{id}', 'LikeMatchController@get');
    $router->delete('likematch/{id}', 'likeMatchController@delete');

    // api/v1/like
    $router->post('like', 'LikeController@post');

    // api/v1/user
    $router->get('user/potentialmatches/{id}', 'UserController@getPotentialMatches');
});
