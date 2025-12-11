<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'factura_id',
        'descripcion',
        'cantidad',
        'precio_unitario',
        'iva',

        // CAMPOS NUEVOS
        'bonificacion_porcentaje',
        'bonificacion_importe',
        'subtotal_sin_iva',
        'subtotal_con_iva',
        'subtotal',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
