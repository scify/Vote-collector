<?php

use Illuminate\Database\Seeder;

class VoteObjectiveTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vote_objectives')->delete();

        App\VoteObjective::create(['title' => 'Arthro', 'description' => 'Fovero arthro']);
        App\VoteObjective::create(['title' => 'Tropologia', 'description' => 'Perigrafh tropologias']);
    }
}