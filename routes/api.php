<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Mahasiswa;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/products', function () {
    return response()->json(Product::all());
});

Route::get('/mahasiswa', function () {
    return response()->json(Mahasiswa::all());
});
