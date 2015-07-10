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
        App\VoteObjective::create(['title' => 'Dokimi', 'description' => 'Collaboratively administrate empowered markets via plug-and-play networks. Dynamically procrastinate B2C users after installed base benefits. Dramatically visualize customer directed convergence without revolutionary ROI. Efficiently unleash cross-media information without cross-media value. Quickly maximize timely deliverables for real-time schemas. Dramatically maintain clicks-and-mortar solutions without functional solutions. Completely synergize resource sucking relationships via premier niche markets. Professionally cultivate one-to-one customer service with robust ideas. Dynamically innovate resource-leveling customer service for state of the art customer service. Objectively innovate empowered manufactured products whereas parallel platforms. Holisticly predominate extensible testing procedures for reliable supply chains. Dramatically engage top-line web services vis-a-vis cutting-edge deliverables.']);
    }
}