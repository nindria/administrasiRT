<?php

use App\Http\Controllers\DataIplController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\NoRumahController;
use App\Http\Controllers\VerifikasiWargaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('norumah', NoRumahController::class);

Route::resource('datawarga', DataWargaController::class);

Route::resource('dataipl', DataIplController::class);

Route::resource('verifikasiwarga', VerifikasiWargaController::class)->only([
    'index', 'show'
]);

Route::post('verifikasiwarga/{id}/verify', [VerifikasiWargaController::class, 'verify'])
    ->name('verifikasiwarga.verify');
    
Route::post('verifikasiwarga/bulk-verify', [VerifikasiWargaController::class, 'bulkVerify'])
    ->name('verifikasiwarga.bulkVerify');
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
