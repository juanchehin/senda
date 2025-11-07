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
        Schema::table('facturas', function (Blueprint $table) {
            $table->string('tipo_comprobante')->nullable();
            $table->integer('punto_venta')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->tinyInteger('concepto')->nullable();
            $table->string('condicion_venta')->nullable();
            $table->decimal('importe_total', 12, 2)->default(0);
            $table->string('estado')->default('pendiente');
            $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
