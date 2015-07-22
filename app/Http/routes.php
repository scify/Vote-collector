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

Route::get('votings/answers/{id}', 'VotingsController@defaultAnswers');         // Page for setting default answers
Route::get('votings/answers/{id}/edit', 'VotingsController@editAnswers');       // Page for editing default answers
Route::post('votings/answers', 'VotingsController@saveDefaultAnswers');         // For saving or updating default answers

Route::get('votings/reading/{id}', 'VotingsController@reading');        // Page for the first & second reading
Route::post('votings/reading', 'VotingsController@saveAnswers');        // For saving votes from 1st & 2nd reading

Route::get('votings/{id}/download', 'VotingsController@download');      // Download the votes of a voting

Route::post('membersorder', 'MembersController@changeOrder');           // For saving new members order