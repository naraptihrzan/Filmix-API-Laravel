<?php

use App\Http\Controllers\Api\FilmController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

// Otomatis mendaftarkan index, store, show, update, dan destroy
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::apiResource('films', FilmController::class);
