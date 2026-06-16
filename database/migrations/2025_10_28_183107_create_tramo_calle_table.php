<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tramo_calle', function (Blueprint $table) {
            $table->bigIncrements('id_tramo_calle'); // o PK compuesta de id_tramo y id_calle
            $table->unsignedBigInteger('id_tramo');
            $table->unsignedBigInteger('id_calle');
            $table->foreign('id_tramo')->references('id_tramos')->on('tramos')->onDelete('cascade');
            $table->foreign('id_calle')->references('id_calle')->on('calles')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramo_calle');
    }
};
