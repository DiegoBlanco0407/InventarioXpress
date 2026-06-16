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
        Schema::create('almacenes', function (Blueprint $table) {
            $table->bigIncrements('id_almacen');
            $table->string('nombre', 255);
            $table->unsignedBigInteger('id_ciudad');
            
            // Relación con la tabla ciudades
            $table->foreign('id_ciudad')
                  ->references('id_ciudad')
                  ->on('ciudades')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almacenes');
    }
};
