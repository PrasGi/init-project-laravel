<?php

namespace App\Traits;

use ReflectionClass;
use ReflectionMethod;
use App\Attributes\HandleException;
use Illuminate\Http\Request;

trait AutoHandleException
{
    public function callAction($method, $parameters)
    {
        $reflection = new ReflectionClass($this);

        if ($reflection->hasMethod($method)) {
            $refMethod = $reflection->getMethod($method);

            $attributes = $refMethod->getAttributes(HandleException::class);

            if (count($attributes)) {
                // Ambil request jika tersedia dari parameter
                $request = collect($parameters)
                    ->first(fn ($param) => $param instanceof Request);

                // Panggil method controller yang sesungguhnya
                return $this->handleException(function () use ($method, $parameters) {
                    return $this->{$method}(...$parameters); // Memanggil method langsung
                }, $request);
            }
        }

        // Jika tidak ada attribute HandleException
        return parent::callAction($method, $parameters); // Memanggil default callAction
    }
}
