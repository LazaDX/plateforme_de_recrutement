<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReponsesFormulaires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reponses_formulaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postule_offre_id')->constrained('postules_offres')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions_formulaires')->onDelete('cascade');
            $table->string('valeur'); // La réponse à la question
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
        Schema::table('reponses_formulaires', function (Blueprint $table) {
            //
        });
    }
}
