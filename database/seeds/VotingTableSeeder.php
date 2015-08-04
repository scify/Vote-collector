<?php

use Illuminate\Database\Seeder;

class VotingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('votings')->delete();

        App\Voting::create(['title' => 'Ψηφοφορία 1', 'completed' => false, 'voting_type' => 1, 'objective' => 1]);
        App\Voting::create(['title' => 'Ψηφοφορία 2', 'completed' => false, 'voting_type' => 2, 'objective' => 2]);
    }
}