<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->delete();

        App\Group::create(['name' => 'group 1']);
        App\Group::create(['name' => 'group 2']);
    }
}