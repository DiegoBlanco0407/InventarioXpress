<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // pedido_detalle
        if (Schema::hasTable('pedido_detalle') && !Schema::hasColumn('pedido_detalle', 'id_almacen')) {
            Schema::table('pedido_detalle', function (Blueprint $table) {
                $table->unsignedInteger('id_almacen')->after('cantidad');
                $table->foreign('id_almacen')->references('id_almacen')->on('almacenes')->onUpdate('cascade')->onDelete('restrict');
            });
        }

        // instalaciones
        if (Schema::hasTable('instalaciones') && !Schema::hasColumn('instalaciones', 'id_almacen')) {
            Schema::table('instalaciones', function (Blueprint $table) {
                $table->unsignedInteger('id_almacen')->after('a_instalar');
                $table->foreign('id_almacen')->references('id_almacen')->on('almacenes')->onUpdate('cascade')->onDelete('restrict');
            });
        }

        // salidas_detalles
        if (Schema::hasTable('salidas_detalles') && !Schema::hasColumn('salidas_detalles', 'id_almacen')) {
            Schema::table('salidas_detalles', function (Blueprint $table) {
                $table->unsignedInteger('id_almacen')->after('cantidad');
                $table->foreign('id_almacen')->references('id_almacen')->on('almacenes')->onUpdate('cascade')->onDelete('restrict');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pedido_detalle') && Schema::hasColumn('pedido_detalle', 'id_almacen')) {
            Schema::table('pedido_detalle', function (Blueprint $table) {
                $table->dropForeign(['id_almacen']);
                $table->dropColumn('id_almacen');
            });
        }
        if (Schema::hasTable('instalaciones') && Schema::hasColumn('instalaciones', 'id_almacen')) {
            Schema::table('instalaciones', function (Blueprint $table) {
                $table->dropForeign(['id_almacen']);
                $table->dropColumn('id_almacen');
            });
        }
        if (Schema::hasTable('salidas_detalles') && Schema::hasColumn('salidas_detalles', 'id_almacen')) {
            Schema::table('salidas_detalles', function (Blueprint $table) {
                $table->dropForeign(['id_almacen']);
                $table->dropColumn('id_almacen');
            });
        }
    }
};
