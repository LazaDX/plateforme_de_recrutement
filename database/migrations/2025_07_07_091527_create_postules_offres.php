<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostulesOffres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postules_offres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offre_id')->constrained('offres')->onDelete('cascade');
            $table->foreignId('enqueteur_id')->constrained('enqueteurs')->onDelete('cascade');
            $table->date('date_postule');
            $table->string('type_enqueteur');
            $table->string('status_postule')->default('en attente'); // 'accepté', 'rejeté'
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
        Schema::table('postules_offres', function (Blueprint $table) {
            //
        });
    }
}
