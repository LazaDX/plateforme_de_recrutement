<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffres extends Migration
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
            $table->string('nom_enquete');
            $table->text('details_enquete');
            $table->date('date_debut')->nullable();
            $table->date('date_limite')->nullable();
            $table->foreignId('administrateur_id')->constrained('administrateurs')->onDelete('cascade');
            $table->string('status_offre'); // 'broullion', 'publiée', 'fermée'
            $table->string('priorite')->default('normal'); // 'urgent', 'haute',
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
        Schema::table('offres', function (Blueprint $table) {
            //
        });
    }
}
