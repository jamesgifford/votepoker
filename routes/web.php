<?php

/* Core
-----------------------------------------------------------------------------*/

Route::group(['middleware' => 'auth.temporary'], function() {

    // Room
    Route::get('room/{room}', 'Core\RoomController@show');
    Route::post('room/{room}', 'Core\RoomController@get');
    Route::post('room/{room}/users', 'Core\RoomController@getUsers');
    Route::post('room', 'Core\RoomController@store');

    // Topic
    Route::post('topic', 'Core\TopicController@store');
    Route::put('topic/{topic}/{action?}', 'Core\TopicController@update');

});


/* User
-----------------------------------------------------------------------------*/

// Dashboard
Route::get('/home', 'User\DashboardController@show');


/* Open
-----------------------------------------------------------------------------*/

// Welcome
Route::get('/', 'Open\WelcomeController@show');
