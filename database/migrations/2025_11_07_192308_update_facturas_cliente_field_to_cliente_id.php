<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            // Solo eliminar la columna antigua si existe
            if (Schema::hasColumn('facturas', 'cliente')) {
                $table->dropColumn('cliente');
            }

            // Agregar relación si no existe
            if (!Schema::hasColumn('facturas', 'cliente_id')) {
                $table->unsignedBigInteger('cliente_id')->nullable()->after('id');
                $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('set null');
            }
        });
    }


    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropColumn('cliente_id');
            $table->string('cliente')->nullable();
        });
    }
};
