<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $fillable = [
        'context',
        'action',
        'related_id',
        'related_type',
        'level',
        'message',
        'data',
        'user_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
