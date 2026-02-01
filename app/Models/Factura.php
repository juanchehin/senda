<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';

    /* =============================
       CAMPOS MASIVOS (FILLABLE)
    ============================== */
    protected $fillable = [

        // Relaciones
        'cliente_id',

        // Datos AFIP / comprobante
        'tipo_comprobante',              // A / B / C
        'punto_venta',
        'fecha_emision',
        'concepto',
        'condicion_venta',
        'moneda',
        'valor_dolar',

        // Estado AFIP
        'estado',
        'cae',
        'vto_cae',
        'numero_comprobante_afip',
        'aprobado_por',

        // Totales
        'subtotal',
        'total_iva',
        'importe_total',
        'importe_total_otros_tributos',

        // Percepción IVA
        'percepcion_iva_detalle',
        'percepcion_iva_base',
        'percepcion_iva_alicuota',
        'percepcion_iva_importe',

        // Percepción IIBB
        'percepcion_iibb_detalle',
        'percepcion_iibb_base',
        'percepcion_iibb_alicuota',
        'percepcion_iibb_importe',

        // Fechas comerciales
        'fecha_desde',
        'fecha_hasta',
        'vencimiento_pago',

        // Otros
        'observaciones',
        'creado_por',
    ];

    /* =============================
       CASTS (MUY IMPORTANTE)
    ============================== */
    protected $casts = [
        'fecha_emision'    => 'date',
        'vto_cae'          => 'date',

        'fecha_desde'      => 'datetime',
        'fecha_hasta'      => 'datetime',
        'vencimiento_pago' => 'datetime',

        'subtotal'                     => 'decimal:2',
        'total_iva'                    => 'decimal:2',
        'importe_total'                => 'decimal:2',
        'importe_total_otros_tributos' => 'decimal:2',

        'percepcion_iva_base'       => 'decimal:2',
        'percepcion_iva_alicuota'   => 'decimal:2',
        'percepcion_iva_importe'    => 'decimal:2',

        'percepcion_iibb_base'      => 'decimal:2',
        'percepcion_iibb_alicuota'  => 'decimal:2',
        'percepcion_iibb_importe'   => 'decimal:2',

        'valor_dolar' => 'decimal:2',
    ];

    /* =============================
       ACCESSORS ÚTILES AFIP
    ============================== */

    /**
     * Código AFIP del tipo de comprobante
     * A = 1 | B = 6 | C = 11
     */
    public function getTipoComprobanteAfipAttribute()
    {
        return match ($this->tipo_comprobante) {
            'A' => 1,
            'B' => 6,
            'C' => 11,
            default => null,
        };
    }

    /**
     * Moneda AFIP (AFIP exige PES / USD)
     */
    public function getMonedaAfipAttribute()
    {
        return $this->moneda === 'ARS' ? 'PES' : $this->moneda;
    }

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

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}
