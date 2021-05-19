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

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->group(['prefix' => 'v1'], function () use ($router) {

        // api/v1/users
        $router->get('/user', 'UserController@get');
        $router->post('/user', 'UserController@post');
        $router->put('/user/{id}', 'UserController@put');
        $router->delete('/user/{id}', 'UserController@delete');

        // api/v1/codesnippets
        $router->get('/codesnippet/{id}', 'CodesnippetController@getByUserId');
        $router->post('/codesnippet', 'CodesnippetController@post');
        $router->put('/codesnippet/{id}', 'CodesnippetController@put');
        $router->delete('/codesnippet/{id}', 'CodesnippetController@delete');

    });
});
