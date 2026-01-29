<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'razon_social',
        'cuit',
        'condicion_iva',
        'condicion_iibb',
        'indice',
        'direccion',
        'email',
        'telefono',
        'codigo_postal',
        'localidad',
        'provincia',
        'pais',
        'tipo_doc',
        'nro_doc',
        'observaciones',
    ];

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    public function getCondicionIvaTextoAttribute()
    {
        return match ($this->condicion_iva) {
            'RI'   => 'Responsable Inscripto',
            'MT'   => 'Responsable Monotributo',
            'MS'   => 'Monotributista Social',
            'MTIP' => 'Monotributista Trabajador Independiente Promovido',
            'CF'   => 'Consumidor Final',
            'EX'   => 'IVA Sujeto Exento',
            'NC'   => 'Sujeto No Categorizado',
            'PE'   => 'Proveedor del Exterior',
            'CE'   => 'Cliente del Exterior',
            'IL'   => 'IVA Liberado - Ley N° 19.640',
            'NA'   => 'IVA No Alcanzado',
            default => $this->condicion_iva,
        };
    }


    public function getCondicionIibbTextoAttribute()
    {
        return match ($this->condicion_iibb) {
            'L'  => 'Local',
            'CM' => 'Convenio Multilateral',
            default => '-',
        };
    }

}
