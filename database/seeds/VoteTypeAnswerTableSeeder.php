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

        App\VoteTypeAnswer::create(['type' => 1, 'answer' => 'Nai']);
        App\VoteTypeAnswer::create(['type' => 1, 'answer' => 'Oxi']);
        App\VoteTypeAnswer::create(['type' => 1, 'answer' => 'Parwn']);

        App\VoteTypeAnswer::create(['type' => 2, 'answer' => 'Giwrgos']);
        App\VoteTypeAnswer::create(['type' => 2, 'answer' => 'Mixalis']);
    }
}