<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('directions')->insert([
            ['id' => 1, 'nom_direction' => 'DSIC'],
            ['id' => 2, 'nom_direction' => 'DFRS'],
        ]);
    }
}
