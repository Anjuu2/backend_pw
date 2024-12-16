<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PembayaranController;
Use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RiwayatController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AkunController::class, 'register']);
Route::post('/login', [AkunController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/admin/deposit', [DepositController::class, "Adminindex"]);
    Route::get('/admin/deposit/show/{id}', [DepositController::class, "Adminshow"]);
    Route::post('/admin/deposit/update/{id}', [DepositController::class, "Adminupdate"]);
    Route::delete('/admin/deposit/delete/{id}', [DepositController::class, "Admindelete"]);

    Route::get('/admin/pembayaran', [PembayaranController::class, "Adminindex"]);
    Route::get('/admin/pembayaran/show/{id}', [PembayaranController::class, "Adminshow"]);
    Route::post('/admin/pembayaran/update/{id}', [PembayaranController::class, "Adminupdate"]);
    Route::delete('/admin/pembayaran/delete/{id}', [PembayaranController::class, "Admindelete"]);

    Route::get('/admin/peminjaman', [PeminjamanController::class, "Adminindex"]);
    Route::get('/admin/peminjaman/show/{id}', [PeminjamanController::class, "Adminshow"]);
    Route::post('/admin/peminjaman/update/{id}', [PeminjamanController::class, "Adminupdate"]);
    Route::delete('/admin/peminjaman/delete/{id}', [PeminjamanController::class, "Admindelete"]);

    Route::get('/deposit', [DepositController::class, "index"]);
    Route::post('/deposit/create', [DepositController::class, "create"]);
    Route::get('/deposit/show/{id}', [DepositController::class, "show"]);

    Route::get('/pembayaran', [PembayaranController::class, "index"]);
    Route::post('/pembayaran/create', [PembayaranController::class, "create"]);
    Route::get('/pembayaran/show/{id}', [PembayaranController::class, "show"]);

    Route::get('/peminjaman', [PeminjamanController::class, "index"]);
    Route::post('/peminjaman/create', [PeminjamanController::class, "create"]);
    Route::get('/peminjaman/show/{id}', [PeminjamanController::class, "show"]);
    
    Route::get('/riwayat', [RiwayatController::class, "index"]);
    Route::post('/riwayat/create', [RiwayatController::class, "create"]);

    Route::post('/logout', [AkunController::class, 'logout']);
    Route::get('/show/{id}', [AkunController::class, 'show']);
});

Route::get('/nama/{id}', [AkunController::class, 'showNamaAkun']);