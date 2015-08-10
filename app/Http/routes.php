<?php

Route::get('/', function () {
    return view('welcome');
});

Route::resource('groups', 'GroupsController');
Route::resource('members', 'MembersController');
Route::resource('votetypes', 'VoteTypesController');
Route::resource('voteobjectives', 'VoteObjectivesController');
Route::resource('votings', 'VotingsController');

Route::get('votings/answers/{id}', 'VotingsController@defaultAnswers');     // Page for setting default answers
Route::get('votings/answers/{id}/edit', 'VotingsController@editAnswers');   // Page for editing default answers
Route::post('votings/answers', 'VotingsController@saveDefaultAnswers');     // For saving or updating default answers

Route::get('votings/reading/{id}', 'VotingsController@reading');            // Page for the first & second reading
Route::post('votings/reading', 'VotingsController@saveAnswers');            // For saving votes from 1st & 2nd reading
Route::post('votings/reading/dv', 'VotingsController@deleteVote');          // For deleting a vote (needs voting & member id)
Route::post('votings/complete/{id}', 'VotingsController@markAsComplete');   // For deleting a vote (needs voting & member id)

Route::get('votings/{id}/download', 'VotingsController@download');          // Download the votes of a voting

Route::post('membersorder', 'MembersController@changeOrder');               // For saving new members order
Route::get('membersexport', 'MembersController@exportMembers');             // For exporting members' names and ids to json