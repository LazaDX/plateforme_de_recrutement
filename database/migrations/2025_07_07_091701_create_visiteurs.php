<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisiteurs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visiteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_id')->constrained('offres')->onDelete('cascade');
            $table->integer('nombre_visiteurs')->default(0);
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
        Schema::table('visiteurs', function (Blueprint $table) {
            //
        });
    }
}
