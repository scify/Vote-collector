<?php

use Illuminate\Database\Seeder;


class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('districts')->delete();

        App\District::create(['name' => 'Α Αθηνών']);
        App\District::create(['name' => 'Α Θεσσαλονίκης']);
        App\District::create(['name' => 'Α Πειραιά']);
        App\District::create(['name' => 'Αιτωλίας και Ακαρνανίας']);
        App\District::create(['name' => 'Αργολίδος']);
        App\District::create(['name' => 'Αρκαδίας']);
        App\District::create(['name' => 'Άρτης']);
        App\District::create(['name' => 'Αττικής']);
        App\District::create(['name' => 'Αχαΐας']);
        App\District::create(['name' => 'Β Αθηνών']);
        App\District::create(['name' => 'Β Θεσσαλονίκης']);
        App\District::create(['name' => 'Β Πειραιά']);
        App\District::create(['name' => 'Βοιωτίας']);
        App\District::create(['name' => 'Γρεβενών']);
        App\District::create(['name' => 'Δράμας']);
        App\District::create(['name' => 'Δωδεκανήσου']);
        App\District::create(['name' => 'Έβρου']);
        App\District::create(['name' => 'Επικρατείας']);
        App\District::create(['name' => 'Ευβοίας']);
        App\District::create(['name' => 'Ευρυτανίας']);
        App\District::create(['name' => 'Ζακύνθου']);
        App\District::create(['name' => 'Ηλείας']);
        App\District::create(['name' => 'Ημαθίας']);
        App\District::create(['name' => 'Ηρακλείου']);
        App\District::create(['name' => 'Θεσπρωτίας']);
        App\District::create(['name' => 'Ιωαννίνων']);
        App\District::create(['name' => 'Καβάλας']);
        App\District::create(['name' => 'Καρδίτσης']);
        App\District::create(['name' => 'Καστοριάς']);
        App\District::create(['name' => 'Κερκύρας']);
        App\District::create(['name' => 'Κεφαλληνίας']);
        App\District::create(['name' => 'Κιλκίς']);
        App\District::create(['name' => 'Κοζάνης']);
        App\District::create(['name' => 'Κορινθίας']);
        App\District::create(['name' => 'Κυκλάδων']);
        App\District::create(['name' => 'Λακωνίας']);
        App\District::create(['name' => 'Λαρίσης']);
        App\District::create(['name' => 'Λασιθίου']);
        App\District::create(['name' => 'Λέσβου']);
        App\District::create(['name' => 'Λευκάδος']);
        App\District::create(['name' => 'Μαγνησίας']);
        App\District::create(['name' => 'Μεσσηνίας']);
        App\District::create(['name' => 'Ξάνθης']);
        App\District::create(['name' => 'Πέλλης']);
        App\District::create(['name' => 'Πιερίας']);
        App\District::create(['name' => 'Πρεβέζης']);
        App\District::create(['name' => 'Ρεθύμνης']);
        App\District::create(['name' => 'Ροδόπης']);
        App\District::create(['name' => 'Σάμου']);
        App\District::create(['name' => 'Σερρών']);
        App\District::create(['name' => 'Τρικάλων']);
        App\District::create(['name' => 'Φθιώτιδας']);
        App\District::create(['name' => 'Φλωρίνης']);
        App\District::create(['name' => 'Φωκίδος']);
        App\District::create(['name' => 'Χαλκιδικής']);
        App\District::create(['name' => 'Χανίων']);
        App\District::create(['name' => 'Χίου']);
    }
} 