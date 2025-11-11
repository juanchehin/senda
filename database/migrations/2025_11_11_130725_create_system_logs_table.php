<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();

            // Tipo o módulo que genera el log (AFIP, usuarios, pedidos, etc.)
            $table->string('context')->nullable();

            // Acción o evento (crear, actualizar, eliminar, enviar, etc.)
            $table->string('action')->nullable();

            // ID relacionado (por ejemplo id de factura, usuario, pedido)
            $table->unsignedBigInteger('related_id')->nullable();

            // Tipo de entidad relacionada (para poder asociar con cualquier modelo)
            $table->string('related_type')->nullable();

            // Nivel del log (info, warning, error, critical)
            $table->string('level', 20)->default('info');

            // Mensaje corto
            $table->string('message')->nullable();

            // Datos extra (request, response, JSON, etc.)
            $table->json('data')->nullable();

            // Usuario que ejecutó la acción (si aplica)
            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
