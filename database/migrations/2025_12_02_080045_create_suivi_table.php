<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suivis', function (Blueprint $table) {
            $table->unsignedBigInteger('suiveur_id');
            $table->unsignedBigInteger('suivi_id');
            $table->foreign('suiveur_id')->references('id')->on('users');
            $table->foreign('suivi_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suivis', function (Blueprint $table) {
            Schema::dropIfExists('suivis');
        });
    }
};
