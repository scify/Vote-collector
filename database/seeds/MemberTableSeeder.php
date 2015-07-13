<?php

use Illuminate\Database\Seeder;

class MemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('members')->delete();

        $m = App\Member::create(['first_name' => 'Sam', 'last_name' => 'Sam', 'order' => 2]);
        $m->groups()->attach([2, 1]);
        $m = App\Member::create(['first_name' => 'Max', 'last_name' => 'Max', 'order' => 1]);
        $m->groups()->attach([1]);
        $m = App\Member::create(['first_name' => 'John', 'last_name' => 'Smith', 'order' => 3]);
        $m->groups()->attach([2]);
    }
}