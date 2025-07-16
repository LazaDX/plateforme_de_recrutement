<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministrateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('administrateurs')->insert([
            [
                'id' => 1,
                'nom' => 'Nambinintsoa',
                'prenom' => 'andry',
                'email' => 'nambinintsoaandry@gmail.com',
                'password' => '$2y$10$RGa.EtWi7tbT9YUwR9iCBexbViZ3qskXCfjxmiEIMUFC7qbd6.9zK',
                'role_id' => 1,
                'poste_id' => 2,
                'direction_id' => 1,
                'status' => 'actif',
            ],
            [
                'id' => 2,
                'nom' => 'Rasoa',
                'prenom' => 'Nomena',
                'email' => 'service1@gmail.com',
                'password' => '$2y$10$7I4Bb1s69H7LKq5IYtiMj.PJOgLPrfYUQXmRud1vCuC3XYp4S6VH2',
                'role_id' => 1,
                'poste_id' => 2,
                'direction_id' => 1,
                'status' => 'inactif',
            ],
            [
                'id' => 3,
                'nom' => 'Ratsaraefadahy',
                'prenom' => 'Narindra Sarobidy',
                'email' => 'narindra@gmail.com',
                'password' => '$2y$10$dxmUvNJMtf4oHFEdYo7TrewLqOdlTO430nOK3FNsR1c3fp.w3zk.m',
                'role_id' => 1,
                'poste_id' => 1,
                'direction_id' => 1,
                'status' => 'actif',
            ],
            [
                'id' => 4,
                'nom' => 'Rakotonarivo',
                'prenom' => 'Ravaka',
                'email' => 'ravaka@gmail.com',
                'password' => '$2y$10$RGa.EtWi7tbT9YUwR9iCBexbViZ3qskXCfjxmiEIMUFC7qbd6.9zK',
                'role_id' => 2,
                'poste_id' => 1,
                'direction_id' => 1,
                'status' => 'actif',
            ],
            [
                'id' => 5,
                'nom' => 'Rasoa',
                'prenom' => 'Narindra',
                'email' => 'coll@gmail.com',
                'password' => '$2y$10$VVZFyhZiMJjZgekQ7bB1ROvGFkF87nD5mfVrPzdLkVfbI3ZqXd/GS',
                'role_id' => 3,
                'poste_id' => 2,
                'direction_id' => 1,
                'status' => 'actif',
            ],
            [
                'id' => 6,
                'nom' => 'Rabe',
                'prenom' => 'Niony',
                'email' => 'serv@gmail.com',
                'password' => '$2y$10$gi304HoWzCV4rALsN54OhOLR/ubmWxJnccfodpQKIALrhhNwMrG0K',
                'role_id' => 3,
                'poste_id' => 2,
                'direction_id' => 1,
                'status' => 'actif',
            ],
            [
                'id' => 7,
                'nom' => 'Rajeriny',
                'prenom' => 'Sed',
                'email' => 'ad@gmail.com',
                'password' => '$2y$10$QzhpsMlSvHM5FKSN6Jskn.l08hefSx9rGDrzePIZLYAvpl3iTpol.',
                'role_id' => 2,
                'poste_id' => 1,
                'direction_id' => 1,
                'status' => 'actif',
            ],
        ]);
    }
}
