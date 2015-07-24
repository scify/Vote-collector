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

        App\Group::create(['name' => 'Ανεξάρτητοι Έλληνες']);
        App\Group::create(['name' => 'Κ.Κ.Ε.']);
        App\Group::create(['name' => 'Ν.Δ.']);
        App\Group::create(['name' => 'ΠΑ.ΣΟ.Κ.']);
        App\Group::create(['name' => 'ΣΥΡΙΖΑ']);
        App\Group::create(['name' => 'Το Ποτάμι']);
        App\Group::create(['name' => 'Χρυσή Αυγή']);
    }
}