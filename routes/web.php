<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BapController;

Route::get('/bap', [BapController::class, 'index'])->name('bap.index');
Route::post('/bap/cetak', [BapController::class, 'cetak'])->name('bap.cetak');