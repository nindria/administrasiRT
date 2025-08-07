<?php

use App\Http\Controllers\DataIplController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\VerifikasiIplController;
use App\Http\Controllers\VerifikasiWargaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('kartukeluarga', KartuKeluargaController::class);

Route::resource('lokasi', LokasiController::class);

Route::resource('datawarga', DataWargaController::class);

Route::resource('event', EventController::class);

Route::resource('dataipl', DataIplController::class)->middleware('auth');

Route::resource('verifikasiwarga', VerifikasiWargaController::class)->only([
    'index',
    'show'
]);


Route::post('verifikasiwarga/{id}/verify', [VerifikasiWargaController::class, 'verify'])
    ->name('verifikasiwarga.verify');

Route::post('verifikasiwarga/bulk-verify', [VerifikasiWargaController::class, 'bulkVerify'])
    ->name('verifikasiwarga.bulkVerify');


Route::resource('verifikasiipl', VerifikasiIplController::class)->only([
    'index',
    'show'
]);

Route::put('/verifikasiipl/{ipl}/verify', [VerifikasiIplController::class, 'verify'])
    ->name('verifikasiipl.verify');
    
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
