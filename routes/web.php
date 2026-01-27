<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Export\ExcelController;
use App\Http\Controllers\Export\PdfController;

// Auth Routes
Route::get('/', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout']);

// Main Routes (Protected)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [BeritaAcaraController::class, 'index'])->name('dashboard');

    // Grouping Berita Acara Routes
    Route::prefix('berita-acara')->name('berita-acara.')->group(function () {
        Route::get('/create', [BeritaAcaraController::class, 'create'])->name('create');
        Route::post('/', [BeritaAcaraController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BeritaAcaraController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BeritaAcaraController::class, 'update'])->name('update');

        Route::get('/{id}/pdf', [BeritaAcaraController::class, 'pdf'])
            ->name('pdf')
            ->middleware('throttle:5,1'); 

        // Server-Side DataTables Routes
        Route::get('/datatable', [BeritaAcaraController::class, 'datatableBap'])->name('berita-acara.datatable');

        Route::get('/export/excel', [ExcelController::class, 'exportExcel'])
            ->name('export.excel')
            ->middleware('throttle:5,1');

        Route::get('/export/pdf-list', [PdfController::class, 'exportPdfList'])
            ->name('export.pdflist')
            ->middleware('throttle:5,1');

        // Admin Only Actions
        Route::middleware(['role:admin'])->group(function () {
            Route::delete('/{id}', [BeritaAcaraController::class, 'destroy'])->name('destroy');
            Route::post('/assign', [BeritaAcaraController::class, 'assignPetugas'])->name('assign'); 
        });
    });
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/berita-acara', [BeritaAcaraController::class, 'adminIndex'])->name('admin.bap.index');
        Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('admin.activity-log');
        Route::get('/activity-log/datatable', [ActivityLogController::class, 'datatableLog'])->name('activity-log.datatable');
    });
});