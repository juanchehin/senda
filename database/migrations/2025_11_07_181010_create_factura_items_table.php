<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factura_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas')->onDelete('cascade');
            $table->string('descripcion');
            $table->decimal('cantidad', 10, 2)->default(1);
            $table->decimal('precio_unitario', 15, 2)->default(0);
            $table->decimal('iva', 5, 2)->nullable()->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_items');
    }
};
