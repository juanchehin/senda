<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
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
        'fecha_desde',
        'fecha_hasta',
        'vencimiento_pago',
        'subtotal',
        'total_iva',
        'importe_total',
    ];

    //
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
