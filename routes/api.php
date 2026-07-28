<?php

use App\Admin\Controllers\Api\ApplicationController;
use App\Admin\Controllers\Api\ApplicationVersionController;
use App\Http\Controllers\Api\PublicApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::prefix('admin')->name('api.admin.')->group(function (): void {
    Route::get('/applications-with-versions', [ApplicationController::class, 'getAllWithVersions'])->name('applications.with-versions');

    Route::apiResource('applications', ApplicationController::class);

    Route::get('/applications/{application}/versions', [ApplicationVersionController::class, 'index'])->name('applications.versions.index');
    Route::post('/applications/{application}/versions', [ApplicationVersionController::class, 'store'])->name('applications.versions.store');

    Route::get('/application-versions/{applicationVersion}', [ApplicationVersionController::class, 'show'])->name('application-versions.show');
    Route::put('/application-versions/{applicationVersion}', [ApplicationVersionController::class, 'update'])->name('application-versions.update');
    Route::delete('/application-versions/{applicationVersion}', [ApplicationVersionController::class, 'destroy'])->name('application-versions.destroy');
});

Route::get('/applications', [PublicApplicationController::class, 'index'])->name('api.applications.index');