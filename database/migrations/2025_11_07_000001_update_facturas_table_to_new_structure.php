<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->renameColumn('tipo', 'tipo_comprobante');
            $table->renameColumn('fecha', 'fecha_emision');
            $table->renameColumn('total', 'importe_total');

            $table->unsignedBigInteger('cliente_id')->nullable()->after('id');
            $table->integer('punto_venta')->nullable()->after('tipo_comprobante');
            $table->tinyInteger('concepto')->nullable()->after('fecha_emision');
            $table->string('condicion_venta')->nullable()->after('concepto');
            $table->unsignedBigInteger('creado_por')->nullable()->after('estado');

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('set null');
            $table->foreign('creado_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->renameColumn('tipo_comprobante', 'tipo');
            $table->renameColumn('fecha_emision', 'fecha');
            $table->renameColumn('importe_total', 'total');

            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['creado_por']);
            $table->dropColumn(['cliente_id', 'punto_venta', 'concepto', 'condicion_venta', 'creado_por']);
        });
    }
};
