<?php

namespace App\Helpers;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function log($context, $action, $message = null, $data = null, $level = 'info', $relatedModel = null)
    {
        SystemLog::create([
            'context' => $context,
            'action' => $action,
            'level' => $level,
            'message' => $message,
            'data' => is_array($data) ? $data : ($data ? ['raw' => $data] : null),
            'user_id' => Auth::id(),
            'related_id' => $relatedModel?->id,
            'related_type' => $relatedModel ? get_class($relatedModel) : null,
        ]);
    }
}
