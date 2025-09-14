<?php

use App\Http\Controllers\BannerController;
// use App\Http\Controllers\DataIplController; // Disabled - not used
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
// Roles routes with permissions

Route::resource('roles', RoleController::class)
    ->only(['create', 'store'])
    ->middleware('permission:roles.create');

Route::resource('roles', RoleController::class)
    ->only(['edit', 'update'])
    ->middleware('permission:roles.edit');

Route::resource('roles', RoleController::class)
    ->only(['destroy'])
    ->middleware('permission:roles.delete');

Route::resource('roles', RoleController::class)
    ->only(['index', 'show'])
    ->middleware('permission:roles.view|roles.create|roles.edit|roles.delete');

// Kartu Keluarga routes with permissions
Route::resource('kartukeluarga', KartuKeluargaController::class)
    ->only(['create', 'store'])
    ->middleware('permission:kartukeluarga.create');

Route::resource('kartukeluarga', KartuKeluargaController::class)
    ->only(['edit', 'update'])
    ->middleware('permission:kartukeluarga.edit');

Route::resource('kartukeluarga', KartuKeluargaController::class)
    ->only(['destroy'])
    ->middleware('permission:kartukeluarga.delete');

Route::resource('kartukeluarga', KartuKeluargaController::class)
    ->only(['index', 'show'])
    ->middleware('permission:kartukeluarga.view|kartukeluarga.create|kartukeluarga.edit|kartukeluarga.delete');

// Rumah routes with permissions
Route::resource('rumah', RumahController::class)
    ->only(['create', 'store'])
    ->middleware('permission:rumah.create');

Route::resource('rumah', RumahController::class)
    ->only(['edit', 'update'])
    ->middleware('permission:rumah.edit');

Route::resource('rumah', RumahController::class)
    ->only(['destroy'])
    ->middleware('permission:rumah.delete');

Route::resource('rumah', RumahController::class)
    ->only(['index', 'show'])
    ->middleware('permission:rumah.view|rumah.create|rumah.edit|rumah.delete');

// Data Warga routes with permissions
Route::resource('datawarga', DataWargaController::class)
    ->only(['create', 'store'])
    ->middleware('permission:datawarga.create');

Route::resource('datawarga', DataWargaController::class)
    ->only(['edit', 'update'])
    ->middleware('permission:datawarga.edit');

Route::resource('datawarga', DataWargaController::class)
    ->only(['destroy'])
    ->middleware('permission:datawarga.delete');

Route::resource('datawarga', DataWargaController::class)
    ->only(['index', 'show'])
    ->middleware('permission:datawarga.view|datawarga.create|datawarga.edit|datawarga.delete');

Route::post('datawarga/store-multiple', [DataWargaController::class, 'storeMultiple'])
    ->name('datawarga.storeMultiple')
    ->middleware('permission:datawarga.create');

// Events routes with permissions
Route::resource('events', EventController::class)
    ->only(['create', 'store'])
    ->middleware('permission:events.create');

Route::resource('events', EventController::class)
    ->only(['edit', 'update'])
    ->middleware('permission:events.edit');

Route::resource('events', EventController::class)
    ->only(['destroy'])
    ->middleware('permission:events.delete');

Route::resource('events', EventController::class)
    ->only(['index'])
    ->middleware('permission:events.view|events.create|events.edit|events.delete');

Route::resource('events', EventController::class)
    ->only(['show']);

// Banners routes with permissions
Route::resource('banners', BannerController::class)
    ->only(['create', 'store'])
    ->middleware(['auth', 'permission:banners.create']);

Route::resource('banners', BannerController::class)
    ->only(['edit', 'update'])
    ->middleware(['auth', 'permission:banners.edit']);

Route::resource('banners', BannerController::class)
    ->only(['destroy'])
    ->middleware(['auth', 'permission:banners.delete']);

Route::resource('banners', BannerController::class)
    ->only(['index', 'show'])
    ->middleware(['auth', 'permission:banners.view|banners.create|banners.edit|banners.delete']);

// Data IPL routes with permissions - Disabled
// Route::resource('dataipl', DataIplController::class)
//     ->only(['create', 'store'])
//     ->middleware(['auth', 'permission:dataipl.create']);

// Route::resource('dataipl', DataIplController::class)
//     ->only(['edit', 'update'])
//     ->middleware(['auth', 'permission:dataipl.edit']);

// Route::resource('dataipl', DataIplController::class)
//     ->only(['destroy'])
//     ->middleware(['auth', 'permission:dataipl.delete']);

// Route::resource('dataipl', DataIplController::class)
//     ->only(['index', 'show'])
//     ->middleware(['auth', 'permission:dataipl.view|dataipl.create|dataipl.edit|dataipl.delete']);

// Verifikasi Warga routes with permissions
Route::resource('verifikasiwarga', VerifikasiWargaController::class)
    ->only(['create', 'store'])
    ->middleware('permission:verifikasiwarga.create');

Route::resource('verifikasiwarga', VerifikasiWargaController::class)
    ->only(['edit', 'update'])
    ->middleware('permission:verifikasiwarga.edit');

Route::resource('verifikasiwarga', VerifikasiWargaController::class)
    ->only(['destroy'])
    ->middleware('permission:verifikasiwarga.delete');

Route::resource('verifikasiwarga', VerifikasiWargaController::class)
    ->only(['index', 'show'])
    ->middleware('permission:verifikasiwarga.view|verifikasiwarga.create|verifikasiwarga.edit|verifikasiwarga.delete');

// Verifikasi IPL routes with permissions
Route::resource('verifikasiipl', VerifikasiIplController::class)
    ->only(['index', 'show'])
    ->middleware('permission:verifikasiipl.view|verifikasiipl.create|verifikasiipl.edit|verifikasiipl.delete');

Route::put('/verifikasiipl/{ipl}/verify', [VerifikasiIplController::class, 'verify'])
    ->name('verifikasiipl.verify')
    ->middleware('permission:verifikasiipl.edit');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
