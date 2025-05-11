<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use ReflectionClass;

trait Loggable
{
    public function __construct(...$args)
    {
        Log::info("[Loggable] Class instantiated: " . static::class, ['args' => $args]);
        // Call original constructor if it exists
        if (method_exists(get_parent_class($this), '__construct')) {
            parent::__construct(...$args);
        }
    }

    public function __call($method, $args)
    {
        Log::info("[Loggable] Calling method: {$method} on " . static::class, ['args' => $args]);

        return call_user_func_array([$this, $method], $args);
    }
}
