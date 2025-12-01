<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remito extends Model
{
    protected $fillable = [
        'cliente_id',
        'creado_por',
        'numero_remito',
        'fecha',
        'estado',
        'observaciones',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items()
    {
        return $this->hasMany(RemitoItem::class);
    }
}
