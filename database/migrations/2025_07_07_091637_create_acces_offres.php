<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccesOffres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acces_offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_id')->constrained('offres')->onDelete('cascade');
            $table->foreignId('administrateur_id')->constrained('administrateurs')->onDelete('cascade');
            $table->boolean('etat')->default(true);
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
        Schema::table('acces_offres', function (Blueprint $table) {
            //
        });
    }
}
