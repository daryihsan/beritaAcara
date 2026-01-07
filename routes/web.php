<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', [LoginController::class, 'show'])->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [BeritaAcaraController::class, 'index']);
    Route::get('/berita-acara', [BeritaAcaraController::class, 'store']);

    Route::get('/berita-acara/create', [BeritaAcaraController::class, 'create']);

    Route::post('/berita-acara', [BeritaAcaraController::class, 'store']);
    Route::get('/berita-acara/{id}/pdf', [BeritaAcaraController::class, 'pdf']);
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/berita-acara', [BeritaAcaraController::class, 'all']);
        Route::post('/admin/assign', [BeritaAcaraController::class, 'assignPetugas']);
    });

});
