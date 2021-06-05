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


$router->group(['prefix' => 'api'], function (Router $router) {

    $router->group(['prefix' => 'auth'], function (Router $router) {

        $router->post('login', [
            'as' => 'auth.login',
            'uses' => 'AuthController@login'
        ]);

        $router->post('register', [
            'as' => 'auth.register',
            'uses' => 'AuthController@register',
        ]);
    });

    $router->group(['middleware' => 'auth'], function (Router $router) {

        $router->get('test', function () {

            return auth()->user();

        });

    });
});


// protected routes
$router->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function ($router) {

    // api/v1/like-matches
    $router->get('like-match', 'LikeMatchController@getAll');
    $router->get('like-match/{id}', 'LikeMatchController@get');
    $router->delete('like-match/{id}', 'likeMatchController@delete');
    $router->post('like-match/{id}/message', 'MessageController@post');

    // api/v1/like
    $router->post('like', 'LikeController@post');

    // api/v1/user
    $router->get('user/potentialmatches/{id}', 'UserController@getPotentialMatches');
    $router->get('user/{id}', 'UserController@get');

    // api/v1/codesnippets
    $router->get('codesnippet/{userId}', 'CodesnippetController@getByUserId');
    $router->get('codesnippet', 'CodesnippetController@getByAuthId');
    $router->post('codesnippet', 'CodesnippetController@post');
    $router->put('codesnippet/{id}', 'CodesnippetController@put');
    $router->delete('codesnippet/{id}', 'CodesnippetController@delete');
});
