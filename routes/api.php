<?php

declare(strict_types=1);

use App\Http\Resources\ProductResourceCollection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', function () {
    return new ProductResourceCollection(Product::all());
})->middleware('auth:sanctum');
