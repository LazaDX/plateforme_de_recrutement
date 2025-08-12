<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChampsToReponsesFormulaires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reponses_formulaires', function (Blueprint $table) {
            $table->string('fichier_path')->nullable()->after('valeur');
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
            $table->dropColumn('fichier_path');
        });
    }
}
