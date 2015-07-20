<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call('DistrictTableSeeder');
        $this->call('GroupTableSeeder');
        $this->call('MemberTableSeeder');
        $this->call('VoteObjectiveTableSeeder');
        $this->call('VoteTypeSeeder');
        $this->call('VoteTypeAnswerTableSeeder');
        $this->call('VotingTableSeeder');
        $this->call('VoteTableSeeder');

        Model::reguard();
    }
}