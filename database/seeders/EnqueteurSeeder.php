<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnqueteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enqueteurs')->insert([
            [
                'id' => 1,
                'nom' => 'Ratsaraefadahy',
                'prenom' => 'Narindra sarobidy',
                'email' => 'sarobidy@gmail.com',
                'password' => '$2y$10$Egm1AqbZFAx2OPAn2Oaxaeyuu1JJd3Poj2FkGHh7EI1eAaoyUQMOS',
                'date_de_naissance' => '2022-10-28',
                'photo' => '2022101514435.jpg',
                'diplomes' => "-Bacc\r\n-Licence",
                'experiences' => 'dgggfrhgf',
            ],
            [
                'id' => 2,
                'nom' => 'Randrianantenaina',
                'prenom' => 'Elysa',
                'email' => 'elysa@gmail.com',
                'password' => '$2y$10$0vWUhrLToQhhYj41pzrD3.IugGKzh9ozhz.GKTiDm9vzJjctLnWCC',
                'date_de_naissance' => '1995-01-27',
                'photo' => 'agent2.jpg',
                'diplomes' => "- Bacc,\r\n- Licences",
                'experiences' => null,
            ],
            [
                'id' => 3,
                'nom' => 'Rasoarinandrianina',
                'prenom' => 'Sitraka',
                'email' => 'sitraka@gmail.com',
                'password' => '$2y$10$sz/lxKEE7pxqB8NZCqLF6ejRuzSMD27A3RKylu36njfA24xYOkF6O',
                'date_de_naissance' => '2000-01-06',
                'photo' => 'agent.jpg',
                'diplomes' => 'LIcence,Master',
                'experiences' => '3 ans experiences',
            ],
            [
                'id' => 4,
                'nom' => 'NAMBININTSOA',
                'prenom' => 'Andry',
                'email' => 'nambinintsoaandry@gmail.com',
                'password' => '$2y$10$.nOWs6bbUfoR0fUOEFscJe1NLTcf6/9zbYLK6kFNMMMe9K3wslz3i',
                'date_de_naissance' => '2000-03-16',
                'photo' => 'andrana.jpg',
                'diplomes' => 'dea',
                'experiences' => "enqueteur et chef d'équipe",
            ],
        ]);
    }
}
