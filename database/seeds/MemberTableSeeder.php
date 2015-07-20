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

        // Perifereia 1
        $m = App\Member::create(['first_name' => 'John', 'last_name' => 'Smith', 'order' => 1, 'perifereia' => 1]);
        $m->groups()->attach([2]);
        $m = App\Member::create(['first_name' => 'Sam', 'last_name' => 'Sam', 'order' => 2, 'perifereia' => 1]);
        $m->groups()->attach([2, 1]);

        // Perifereia 2
        $m = App\Member::create(['first_name' => 'Max', 'last_name' => 'Max', 'order' => 1, 'perifereia' => 2]);
        $m->groups()->attach([1]);
    }
}