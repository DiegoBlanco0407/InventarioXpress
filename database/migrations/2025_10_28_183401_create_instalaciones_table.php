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
        Schema::create('instalaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tramo');
            $table->unsignedBigInteger('id_material');
            $table->integer('instalado');
            $table->integer('a_instalar');
            $table->primary(['id_tramo', 'id_material']);
            $table->foreign('id_tramo')->references('id_tramos')->on('tramos')->onDelete('cascade');
            $table->foreign('id_material')->references('id_material')->on('materiales')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instalaciones');
    }
};
