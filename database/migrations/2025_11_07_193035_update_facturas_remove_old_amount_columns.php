<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            if (Schema::hasColumn('facturas', 'importe_neto')) {
                $table->dropColumn('importe_neto');
            }
            if (Schema::hasColumn('facturas', 'iva')) {
                $table->dropColumn('iva');
            }
            if (Schema::hasColumn('facturas', 'total')) {
                $table->dropColumn('total');
            }

            if (!Schema::hasColumn('facturas', 'importe_total')) {
                $table->decimal('importe_total', 15, 2)->default(0)->after('condicion_venta');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            if (Schema::hasColumn('facturas', 'importe_total')) {
                $table->dropColumn('importe_total');
            }

            $table->decimal('importe_neto', 12, 2)->default(0);
            $table->decimal('iva', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
        });
    }
};
