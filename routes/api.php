<?php

use App\Http\Controllers\Api\FilmController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BookmarkController; 
use App\Http\Controllers\Api\UserController; // Pastikan ini sudah terimpor
use Illuminate\Support\Facades\Route;

// --- Public Routes (Bisa diakses tanpa login) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Protected Routes (Wajib Login/Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Fitur Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    /* 
       1. Akses untuk SEMUA (Member & Admin)
       Film, Kategori, Bookmark, dan Profile Pribadi
    */
    
    // Film & Kategori
    Route::get('/films', [FilmController::class, 'index']);
    Route::get('/films/{id}', [FilmController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

    // Bookmark
    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks/toggle', [BookmarkController::class, 'toggleBookmark']);

    // --- FITUR BARU: Profile ---
    Route::get('/user/me', [UserController::class, 'me']);       // Ambil data profil saya
    Route::put('/user/update', [UserController::class, 'update']); // Update data profil saya

    /* 
       2. Akses KHUSUS Admin 
       Mengelola data Film dan Kategori (CRUD)
    */
    Route::middleware('role:admin')->group(function () {
        
        // Mengelola Film
        Route::post('/films', [FilmController::class, 'store']);
        Route::put('/films/{id}', [FilmController::class, 'update']);
        Route::delete('/films/{id}', [FilmController::class, 'destroy']);

        // Mengelola Kategori
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
        
    });

});