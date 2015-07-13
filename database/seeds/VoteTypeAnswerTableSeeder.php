<?php

use Illuminate\Database\Seeder;

class VoteTypeAnswerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vote_type_answers')->delete();

        App\VoteTypeAnswer::create(['type' => 1, 'answer' => 'Ναι']);
        App\VoteTypeAnswer::create(['type' => 1, 'answer' => 'Όχι']);
        App\VoteTypeAnswer::create(['type' => 1, 'answer' => 'Παρών']);

        App\VoteTypeAnswer::create(['type' => 2, 'answer' => 'Γιώργος']);
        App\VoteTypeAnswer::create(['type' => 2, 'answer' => 'Μιχάλης']);
    }
}