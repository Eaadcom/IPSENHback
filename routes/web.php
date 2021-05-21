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
$router->group(['prefix' => 'api', 'midddleware' => 'auth'], function ($router) {
    // todo ..
});
