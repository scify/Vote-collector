<?php

use Illuminate\Database\Seeder;

class VotingItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('voting_items')->delete();

        App\VotingItem::create(['voting_id' => 1, 'vote_type_id' => 1, 'vote_objective_id' => 2]);
        App\VotingItem::create(['voting_id' => 2, 'vote_type_id' => 1, 'vote_objective_id' => 1]);
    }
}