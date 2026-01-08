<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\BeritaAcaraAdminController;

Route::get('/', [LoginController::class, 'show'])->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);



Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [BeritaAcaraController::class, 'index'])->name('dashboard');
    Route::post('/berita-acara', [BeritaAcaraController::class, 'store'])   ->name('bap.store') ;
    Route::get('/berita-acara/create', [BeritaAcaraController::class, 'create']);
    Route::post('/berita-acara/cetak', [BeritaAcaraController::class, 'cetak'])
        ->name('bap.cetak');
    Route::delete('/berita-acara/{id}', [BeritaAcaraController::class, 'destroy'])->name('berita-acara.destroy');
Route::get('/berita-acara/{id}/pdf', [BeritaAcaraController::class, 'pdf'])->name('berita-acara.pdf');

    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/berita-acara', [BeritaAcaraAdminController::class, 'index']);
        Route::post('/berita-acara/assign', [BeritaAcaraAdminController::class, 'assign']);
    });

});
