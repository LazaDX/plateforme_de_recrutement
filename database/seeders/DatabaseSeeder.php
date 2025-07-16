<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            PosteSeeder::class,
            DirectionSeeder::class,
            AdministrateurSeeder::class,
            EnqueteurSeeder::class,
            RegionSeeder::class,
        ]);
    }
}
