<?php

use Illuminate\Database\Seeder;

class VoteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vote_types')->delete();

        App\VoteType::create(['title' => 'Συνήθης']);
        App\VoteType::create(['title' => 'Προεδρική']);
    }
}