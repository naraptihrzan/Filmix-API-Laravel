<?php

use App\Http\Controllers\Api\FilmController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Contoh route yang hanya bisa diakses jika sudah login
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Kamu bisa pindahkan route Film ke sini nanti
});
// Otomatis mendaftarkan index, store, show, update, dan destroy
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::apiResource('films', FilmController::class);
