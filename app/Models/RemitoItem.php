<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemitoItem extends Model
{
    protected $fillable = [
        'remito_id',
        'descripcion',
        'cantidad',
    ];

    public function remito()
    {
        return $this->belongsTo(Remito::class);
    }
}
