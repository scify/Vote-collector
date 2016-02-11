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

        App\District::create(['name' => 'Α\' Αθηνών', 'ordering' => 2]);
        App\District::create(['name' => 'Α\' Θεσσαλονίκης', 'ordering' => 22]);
        App\District::create(['name' => 'Α\' Πειραιά', 'ordering' => 42]);
        App\District::create(['name' => 'Αιτωλίας και Ακαρνανίας', 'ordering' => 4]);
        App\District::create(['name' => 'Αργολίδος', 'ordering' => 5]);
        App\District::create(['name' => 'Αρκαδίας', 'ordering' => 6]);
        App\District::create(['name' => 'Άρτης', 'ordering' => 7]);
        App\District::create(['name' => 'Αττικής', 'ordering' => 8]);
        App\District::create(['name' => 'Αχαΐας', 'ordering' => 9]);
        App\District::create(['name' => 'Β\' Αθηνών', 'ordering' => 3]);
        App\District::create(['name' => 'Β\' Θεσσαλονίκης', 'ordering' => 23]);
        App\District::create(['name' => 'Β\' Πειραιά', 'ordering' => 43]);
        App\District::create(['name' => 'Βοιωτίας', 'ordering' => 10]);
        App\District::create(['name' => 'Γρεβενών', 'ordering' => 11]);
        App\District::create(['name' => 'Δράμας', 'ordering' => 12]);
        App\District::create(['name' => 'Δωδεκανήσου', 'ordering' => 13]);
        App\District::create(['name' => 'Έβρου', 'ordering' => 14]);
        App\District::create(['name' => 'Επικρατείας', 'ordering' => 1]);
        App\District::create(['name' => 'Ευβοίας', 'ordering' => 15]);
        App\District::create(['name' => 'Ευρυτανίας', 'ordering' => 16]);
        App\District::create(['name' => 'Ζακύνθου', 'ordering' => 17]);
        App\District::create(['name' => 'Ηλείας', 'ordering' => 18]);
        App\District::create(['name' => 'Ημαθίας', 'ordering' => 19]);
        App\District::create(['name' => 'Ηρακλείου', 'ordering' => 20]);
        App\District::create(['name' => 'Θεσπρωτίας', 'ordering' => 21]);
        App\District::create(['name' => 'Ιωαννίνων', 'ordering' => 24]);
        App\District::create(['name' => 'Καβάλας', 'ordering' => 25]);
        App\District::create(['name' => 'Καρδίτσης', 'ordering' => 26]);
        App\District::create(['name' => 'Καστοριάς', 'ordering' => 27]);
        App\District::create(['name' => 'Κερκύρας', 'ordering' => 28]);
        App\District::create(['name' => 'Κεφαλληνίας', 'ordering' => 29]);
        App\District::create(['name' => 'Κιλκίς', 'ordering' => 30]);
        App\District::create(['name' => 'Κοζάνης', 'ordering' => 31]);
        App\District::create(['name' => 'Κορινθίας', 'ordering' => 32]);
        App\District::create(['name' => 'Κυκλάδων', 'ordering' => 33]);
        App\District::create(['name' => 'Λακωνίας', 'ordering' => 34]);
        App\District::create(['name' => 'Λαρίσης', 'ordering' => 35]);
        App\District::create(['name' => 'Λασιθίου', 'ordering' => 36]);
        App\District::create(['name' => 'Λέσβου', 'ordering' => 37]);
        App\District::create(['name' => 'Λευκάδος', 'ordering' => 38]);
        App\District::create(['name' => 'Μαγνησίας', 'ordering' => 39]);
        App\District::create(['name' => 'Μεσσηνίας', 'ordering' => 40]);
        App\District::create(['name' => 'Ξάνθης', 'ordering' => 41]);
        App\District::create(['name' => 'Πέλλης', 'ordering' => 44]);
        App\District::create(['name' => 'Πιερίας', 'ordering' => 45]);
        App\District::create(['name' => 'Πρεβέζης', 'ordering' => 46]);
        App\District::create(['name' => 'Ρεθύμνης', 'ordering' => 47]);
        App\District::create(['name' => 'Ροδόπης', 'ordering' => 48]);
        App\District::create(['name' => 'Σάμου', 'ordering' => 49]);
        App\District::create(['name' => 'Σερρών', 'ordering' => 50]);
        App\District::create(['name' => 'Τρικάλων', 'ordering' => 51]);
        App\District::create(['name' => 'Φθιώτιδας', 'ordering' => 52]);
        App\District::create(['name' => 'Φλωρίνης', 'ordering' => 53]);
        App\District::create(['name' => 'Φωκίδος', 'ordering' => 54]);
        App\District::create(['name' => 'Χαλκιδικής', 'ordering' => 55]);
        App\District::create(['name' => 'Χανίων', 'ordering' => 56]);
        App\District::create(['name' => 'Χίου', 'ordering' => 57]);
    }
}
