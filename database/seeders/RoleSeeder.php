<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => 1, 'nom_role' => 'Super Admin'],
            ['id' => 2, 'nom_role' => 'Admin'],
            ['id' => 3, 'nom_role' => 'Collaborateurs'],
        ]);
    }
}
