<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use ReflectionClass;

trait Loggable
{
    public function __call($method, $args)
    {
        Log::info("[Loggable] Calling method: {$method} on " . static::class, ['args' => $args]);

        return call_user_func_array([$this, $method], $args);
    }
}
