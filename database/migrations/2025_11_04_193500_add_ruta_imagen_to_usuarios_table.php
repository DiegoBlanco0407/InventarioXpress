<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('usuarios', 'ruta_imagen')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('ruta_imagen')->nullable()->after('rol');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('usuarios', 'ruta_imagen')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('ruta_imagen');
            });
        }
    }
};
