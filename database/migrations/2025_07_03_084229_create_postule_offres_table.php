<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostuleOffresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postule_offres', function (Blueprint $table) {
            $table->id();
            $table->date('date_postule');
            $table->string('statut')->default('en attente');
            $table->unsignedBigInteger('offre_id');
            $table->unsignedBigInteger('enqueteur_id');
            $table->foreign('offre_id')->references('id')->on('offres')->onDelete('cascade');
            $table->foreign('enqueteur_id')->references('id')->on('enqueteurs')->onDelete('cascade');
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
        Schema::dropIfExists('postule_offres');
    }
}
