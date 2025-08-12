<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChampsToQuestionsFormulaires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions_formulaires', function (Blueprint $table) {
             $table->text('options')->nullable()->after('obligation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions_formulaires', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
}
