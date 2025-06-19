<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Product API endpoint for getting product details
Route::get('/products/{product}', function (Product $product) {
    return response()->json([
        'id' => $product->id,
        'name' => $product->name,
        'base_price' => $product->base_price,
        'sku' => $product->sku,
    ]);
});
