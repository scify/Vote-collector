<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('groups', 'GroupsController');
Route::resource('members', 'MembersController');
Route::resource('votetypes', 'VoteTypesController');
Route::resource('voteobjectives', 'VoteObjectivesController');
Route::resource('votings', 'VotingsController');

Route::post('membersorder', 'MembersController@changeOrder');