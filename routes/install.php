<?php

use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

/*Route::middleware(['isInstalled', 'XSS'])->group(function () {
    Route::prefix('install')->group(function () {
        Route::get('finalize', [InstallController::class, 'final'])->name('install.finalize');
    });
});*/
Route::get('install/initialize', [InstallController::class, 'index'])->name('install.initialize');
Route::middleware(['XSS'])->prefix('install')->group(function () {
    Route::post('process', [InstallController::class, 'getInstall'])->name('install.process');
    Route::post('finalize', [InstallController::class, 'final'])->name('install.finalize');
});

Route::get('admin/create-release', [InstallController::class, 'releaseForm'])->name('release.form');
Route::post('admin/create-release', [InstallController::class, 'createRelease'])->name('create.release');
