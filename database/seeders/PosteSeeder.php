<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('postes')->insert([
            ['id' => 1, 'nom_poste' => 'Directeur'],
            ['id' => 2, 'nom_poste' => 'Chef de Service'],
        ]);
    }
}
