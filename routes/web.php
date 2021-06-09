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

        $router->group(['middleware' => 'auth:api'], function ($router) {
            $router->delete('logout', [
                'as' => 'auth.logout',
                'uses' => 'AuthController@logout'
            ]);
            $router->post('refresh', [
                'as' => 'auth.refresh',
                'uses' => 'AuthController@refresh'
            ]);
            $router->post('me', [
                'as' => 'auth.user',
                'uses' => 'AuthController@me'
            ]);
        });
    });

    // protected routes
    $router->group(['prefix' => 'v1', 'middleware' => 'auth'], function ($router) {

        // api/v1/like-matches
        $router->get('like-match', [
            'as' => 'like-match.getAll',
            'uses' => 'LikeMatchController@getAll'
        ]);
        $router->get('like-match/{id}', [
            'as' => 'like-match.get',
            'uses' => 'LikeMatchController@get'
        ]);
        $router->delete('like-match/{id}', [
            'as' => 'like-match.delete',
            'uses' => 'LikeMatchController@delete'
        ]);
        $router->post('like-match/{id}/message', [
            'as' => 'message.post',
            'uses' => 'MessageController@post'
        ]);

        // api/v1/like
        $router->post('like', [
            'as' => 'like.post',
            'uses' => 'LikeController@post'
        ]);

        // api/v1/user
        $router->get('user/potentialmatches/{id}', [
            'as' => 'user.getPotentialMatches',
            'uses' => 'UserController@getPotentialMatches'
        ]);
        $router->get('user/{id}', [
            'as' => 'user.get',
            'uses' => 'UserController@get'
        ]);

        // api/v1/codesnippets
        $router->get('codesnippet/{userId}', [
            'as' => 'codesnippet.getByUserId',
            'uses' => 'CodesnippetController@getByUserId'
        ]);
        $router->get('codesnippet', [
            'as' => 'codesnippet.getByAuthId',
            'uses' => 'CodesnippetController@getByAuthId'
        ]);
        $router->post('codesnippet', [
            'as' => 'codesnippet.post',
            'uses' => 'CodesnippetController@post'
        ]);
        $router->put('codesnippet/{id}', [
            'as' => 'codesnippet.put',
            'uses' => 'CodesnippetController@put'
        ]);
        $router->delete('codesnippet/{id}', [
            'as' => 'codesnippet.delete',
            'uses' => 'CodesnippetController@delete'
        ]);
    });
});
