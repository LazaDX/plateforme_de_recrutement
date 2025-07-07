<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministrateurs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId("role_id")->constrained('roles')->onDelete('cascade');
            $table->foreignId("poste_id")->constrained('postes')->onDelete('cascade');
            $table->foreignId("direction_id")->constrained('directions')->onDelete('cascade');
            $table->string('status')->default('actif'); // 'actif', 'inactif', 'suspendu'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('administrateurs', function (Blueprint $table) {

        });
    }
}
