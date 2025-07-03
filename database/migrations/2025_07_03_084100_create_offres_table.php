<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id();
            $table->string("nom_enquete");
            $table->text("description");
            $table->date("date_debut");
            $table->date("date_limite");
            $table->string("status");
            $table->unsignedBigInteger('administrateurs_id');
            $table->foreign('administrateurs_id')->references('id')->on('administrateurs')->onDelete('cascade');
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
        Schema::dropIfExists('offres');
    }
}
