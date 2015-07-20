<?php

use Illuminate\Database\Seeder;


class PerifereiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('perifereies')->delete();

        App\Perifereia::create(['name' => 'Α Αθηνών']);
        App\Perifereia::create(['name' => 'Α Θεσσαλονίκης']);
        App\Perifereia::create(['name' => 'Α Πειραιά']);
        App\Perifereia::create(['name' => 'Αιτωλίας και Ακαρνανίας']);
        App\Perifereia::create(['name' => 'Αργολίδος']);
        App\Perifereia::create(['name' => 'Αρκαδίας']);
        App\Perifereia::create(['name' => 'Άρτης']);
        App\Perifereia::create(['name' => 'Αττικής']);
        App\Perifereia::create(['name' => 'Αχαΐας']);
        App\Perifereia::create(['name' => 'Β Αθηνών']);
        App\Perifereia::create(['name' => 'Β Θεσσαλονίκης']);
        App\Perifereia::create(['name' => 'Β Πειραιά']);
        App\Perifereia::create(['name' => 'Βοιωτίας']);
        App\Perifereia::create(['name' => 'Γρεβενών']);
        App\Perifereia::create(['name' => 'Δράμας']);
        App\Perifereia::create(['name' => 'Δωδεκανήσου']);
        App\Perifereia::create(['name' => 'Έβρου']);
        App\Perifereia::create(['name' => 'Επικρατείας']);
        App\Perifereia::create(['name' => 'Ευβοίας']);
        App\Perifereia::create(['name' => 'Ευρυτανίας']);
        App\Perifereia::create(['name' => 'Ζακύνθου']);
        App\Perifereia::create(['name' => 'Ηλείας']);
        App\Perifereia::create(['name' => 'Ημαθίας']);
        App\Perifereia::create(['name' => 'Ηρακλείου']);
        App\Perifereia::create(['name' => 'Θεσπρωτίας']);
        App\Perifereia::create(['name' => 'Ιωαννίνων']);
        App\Perifereia::create(['name' => 'Καβάλας']);
        App\Perifereia::create(['name' => 'Καρδίτσης']);
        App\Perifereia::create(['name' => 'Καστοριάς']);
        App\Perifereia::create(['name' => 'Κερκύρας']);
        App\Perifereia::create(['name' => 'Κεφαλληνίας']);
        App\Perifereia::create(['name' => 'Κιλκίς']);
        App\Perifereia::create(['name' => 'Κοζάνης']);
        App\Perifereia::create(['name' => 'Κορινθίας']);
        App\Perifereia::create(['name' => 'Κυκλάδων']);
        App\Perifereia::create(['name' => 'Λακωνίας']);
        App\Perifereia::create(['name' => 'Λαρίσης']);
        App\Perifereia::create(['name' => 'Λασιθίου']);
        App\Perifereia::create(['name' => 'Λέσβου']);
        App\Perifereia::create(['name' => 'Λευκάδος']);
        App\Perifereia::create(['name' => 'Μαγνησίας']);
        App\Perifereia::create(['name' => 'Μεσσηνίας']);
        App\Perifereia::create(['name' => 'Ξάνθης']);
        App\Perifereia::create(['name' => 'Πέλλης']);
        App\Perifereia::create(['name' => 'Πιερίας']);
        App\Perifereia::create(['name' => 'Πρεβέζης']);
        App\Perifereia::create(['name' => 'Ρεθύμνης']);
        App\Perifereia::create(['name' => 'Ροδόπης']);
        App\Perifereia::create(['name' => 'Σάμου']);
        App\Perifereia::create(['name' => 'Σερρών']);
        App\Perifereia::create(['name' => 'Τρικάλων']);
        App\Perifereia::create(['name' => 'Φθιώτιδας']);
        App\Perifereia::create(['name' => 'Φλωρίνης']);
        App\Perifereia::create(['name' => 'Φωκίδος']);
        App\Perifereia::create(['name' => 'Χαλκιδικής']);
        App\Perifereia::create(['name' => 'Χανίων']);
        App\Perifereia::create(['name' => 'Χίου']);
    }
} 