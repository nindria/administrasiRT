<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\DataIplController;
use App\Http\Controllers\DataWargaController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KartuKeluargaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifikasiIplController;
use App\Http\Controllers\VerifikasiWargaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $events = \App\Models\Event::latest()->take(4)->get();
    $banner = \App\Models\Banner::active()->latest()->first();

    return Inertia::render('Welcome', [
        'events' => $events,
        'banner' => $banner,
    ]);
})->name('home');

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::resource('users', UserController::class);

// Route::resource('users', UserController::class)
//     ->only(['create', 'store'])
//     ->middleware('permission:users.create');

// Route::resource('users', UserController::class)
//     ->only(['edit', 'update'])
//     ->middleware('permission:users.edit');

// Route::resource('users', UserController::class)
//     ->only(['destroy'])
//     ->middleware('permission:users.delete');

// Route::resource('users', UserController::class)
//     ->only(['index', 'show'])
//     ->middleware('permission:users.view|users.create|users.edit|users.delete');
Route::resource('roles', RoleController::class);

Route::resource('kartukeluarga', KartuKeluargaController::class);

Route::resource('rumah', RumahController::class);

Route::resource('datawarga', DataWargaController::class);
Route::post('datawarga/store-multiple', [DataWargaController::class, 'storeMultiple'])->name('datawarga.storeMultiple');

Route::resource('events', EventController::class);
Route::resource('banners', BannerController::class)->middleware('auth');

Route::resource('dataipl', DataIplController::class)->middleware('auth');

Route::resource('verifikasiwarga', VerifikasiWargaController::class);

// Route::resource('verifikasi', VerifikasiWargaController::class)->only(['index', 'update', 'destroy']);

Route::resource('verifikasiipl', VerifikasiIplController::class)->only([
    'index',
    'show',
]);

Route::put('/verifikasiipl/{ipl}/verify', [VerifikasiIplController::class, 'verify'])
    ->name('verifikasiipl.verify');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
