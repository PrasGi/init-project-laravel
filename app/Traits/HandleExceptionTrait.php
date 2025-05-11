<?php

namespace App\Traits;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Log;

trait HandleExceptionTrait
{
    public function handleException(callable $callback, ?Request $request = null)
    {
        if ($request) {
            Log::info('Incoming request', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'input' => $request->all(),
                'headers' => $request->headers->all(),
            ]);
        }

        try {
            return $callback();
        } catch (Throwable $e) {
            Log::error('Exception caught in handleException', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return ResponseHelper::error(
                message: 'Something has error',
                errors: config('app.debug') ? $e->getMessage() : []
            );
        }
    }
}
