<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

\Lomkit\Rest\Facades\Rest::resource('users', \App\Rest\Controllers\UsersController::class)->middleware('auth:sanctum');
\Lomkit\Rest\Facades\Rest::resource('products', \App\Rest\Controllers\ProductsController::class)->middleware('auth:sanctum');
