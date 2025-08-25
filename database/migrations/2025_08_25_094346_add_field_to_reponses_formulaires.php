<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToReponsesFormulaires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reponses_formulaires', function (Blueprint $table) {
            $table->text('conditions_data')->nullable(); // Pour stocker les données des conditions
            $table->string('condition_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reponses_formulaires', function (Blueprint $table) {
            //
        });
    }
}
