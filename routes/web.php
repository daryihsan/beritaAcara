<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BapController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/bap', [BapController::class, 'index'])->name('bap.form');
Route::post('/bap/cetak', [BapController::class, 'cetak'])->name('bap.cetak');