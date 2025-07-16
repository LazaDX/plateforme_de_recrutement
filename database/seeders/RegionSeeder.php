<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('regions')->insert([
            ['id' => 1, 'nom_region' => 'Diana'],
            ['id' => 2, 'nom_region' => 'Sava'],
            ['id' => 3, 'nom_region' => 'Itasy'],
            ['id' => 4, 'nom_region' => 'Analamanga'],
            ['id' => 5, 'nom_region' => 'Vakinankaratra'],
            ['id' => 6, 'nom_region' => 'Bongolava'],
            ['id' => 7, 'nom_region' => 'Sofia'],
            ['id' => 8, 'nom_region' => 'Boeny'],
            ['id' => 9, 'nom_region' => 'Betsiboka'],
            ['id' => 10, 'nom_region' => 'Melaky'],
            ['id' => 11, 'nom_region' => 'Alaotra-Mangoro'],
            ['id' => 12, 'nom_region' => 'Atsinanana'],
            ['id' => 13, 'nom_region' => 'Analanjirofo'],
            ['id' => 14, 'nom_region' => 'Amoron i Mania'],
            ['id' => 15, 'nom_region' => 'Haute Matsiatra'],
            ['id' => 16, 'nom_region' => 'Vatovavy'],
            ['id' => 17, 'nom_region' => 'Fitovinany'],
            ['id' => 18, 'nom_region' => 'Atsimo-Atsinanana'],
            ['id' => 19, 'nom_region' => 'Ihorombe'],
            ['id' => 20, 'nom_region' => 'Menabe'],
            ['id' => 21, 'nom_region' => 'Atsimo-Andrefana'],
            ['id' => 22, 'nom_region' => 'Androy'],
            ['id' => 23, 'nom_region' => 'Anôsy'],
        ]);
    }
}
