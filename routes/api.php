<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GearController;
use App\Http\Controllers\Api\RentalController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rute Katalog
    Route::get('/gears', [GearController::class, 'index']);
    Route::get('/gears/{gear}', [GearController::class, 'show']);

    // Rute Transaksi Penyewaan (Hanya baca & buat pesanan)
    Route::get('/rentals', [RentalController::class, 'index']);
    Route::post('/rentals', [RentalController::class, 'store']);

    // Rute Khusus Admin
    Route::middleware('admin')->group(function () {
        // Kelola Alat
        Route::post('/gears', [GearController::class, 'store']);
        Route::put('/gears/{gear}', [GearController::class, 'update']);
        Route::delete('/gears/{gear}', [GearController::class, 'destroy']);
        
        // Memproses pengembalian barang & denda
        Route::put('/rentals/{rental}/return', [RentalController::class, 'returnRental']);
    });
});