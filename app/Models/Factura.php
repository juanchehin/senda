<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        // =============================
        // Datos principales
        // =============================
        'cliente_id',
        'tipo_comprobante',
        'punto_venta',
        'fecha_emision',
        'concepto',
        'condicion_venta',
        'moneda',
        'valor_dolar',
        'estado',
        'creado_por',

        // =============================
        // Fechas (servicios)
        // =============================
        'fecha_desde',
        'fecha_hasta',
        'vencimiento_pago',

        // =============================
        // Importes
        // =============================
        'subtotal',
        'total_iva',
        'importe_total',

        // =============================
        // Percepciones IVA
        // =============================
        'percepcion_iva_detalle',
        'percepcion_iva_base',
        'percepcion_iva_alicuota',
        'percepcion_iva_importe',

        // =============================
        // Percepciones Ingresos Brutos
        // =============================
        'percepcion_iibb_detalle',
        'percepcion_iibb_base',
        'percepcion_iibb_alicuota',
        'percepcion_iibb_importe',
    ];

    /* =============================
       RELACIONES
    ============================== */

    public function items()
    {
        return $this->hasMany(FacturaItem::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function remitos()
    {
        return $this->hasMany(FacturaRemito::class);
    }
}
