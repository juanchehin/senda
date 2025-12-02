<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaRemito extends Model
{
    protected $fillable = [
        'factura_id',
        'pto_venta',
        'comprobante',
        'fecha_emision'
    ];

    public function factura() {
        return $this->belongsTo(Factura::class);
    }
}
