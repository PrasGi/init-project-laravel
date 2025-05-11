<?php

use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catch-unauthorized', function () {
    return ResponseHelper::error(
        message: 'Unauthorized',
        errors: ['token' => 'Token has invalid or expired']
    );
})->name('unauthorized');
