<?php

use App\Http\Controllers\DataIplController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\RumahController;
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

Route::resource('rumah', RumahController::class);

Route::resource('datawarga', DataWargaController::class);
Route::post('datawarga/store-multiple', [DataWargaController::class, 'storeMultiple'])->name('datawarga.storeMultiple');

Route::resource('event', EventController::class);

Route::resource('dataipl', DataIplController::class)->middleware('auth');

Route::resource('verifikasiwarga', VerifikasiWargaController::class);


// Route::resource('verifikasi', VerifikasiWargaController::class)->only(['index', 'update', 'destroy']);


Route::resource('verifikasiipl', VerifikasiIplController::class)->only([
    'index',
    'show'
]);

Route::put('/verifikasiipl/{ipl}/verify', [VerifikasiIplController::class, 'verify'])
    ->name('verifikasiipl.verify');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
