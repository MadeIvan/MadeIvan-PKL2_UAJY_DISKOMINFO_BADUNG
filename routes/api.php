<?php

use App\Admin\Controllers\Api\ApplicationController;
use App\Admin\Controllers\Api\ApplicationVersionController;
use App\Admin\Controllers\Api\TutorialNodeController;
use App\Admin\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PublicApplicationController;
use App\Admin\Controllers\Api\TutorialContentBlockController;
use App\Http\Controllers\Api\PublicMateriController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::prefix('admin')->name('api.admin.')->middleware(['auth:sanctum', 'role:Admin'])->group(function (): void {
    Route::apiResource('categories', CategoryController::class);
    Route::post('/categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::get('/applications/options', [ApplicationController::class, 'options'])->name('applications.options');
    Route::get('/applications-with-versions', [ApplicationController::class, 'getAllWithVersions'])->name('applications.with-versions');

    Route::get('/applications/{application}/versions', [ApplicationVersionController::class, 'index'])->whereNumber('application')->name('applications.versions.index');
    Route::post('/applications/{application}/versions', [ApplicationVersionController::class, 'store'])->whereNumber('application')->name('applications.versions.store');

    Route::get('/application-versions/{applicationVersion}', [ApplicationVersionController::class, 'show'])->whereNumber('applicationVersion')->name('application-versions.show');
    Route::put('/application-versions/{applicationVersion}', [ApplicationVersionController::class, 'update'])->whereNumber('applicationVersion')->name('application-versions.update');
    Route::delete('/application-versions/{applicationVersion}', [ApplicationVersionController::class, 'destroy'])->whereNumber('applicationVersion')->name('application-versions.destroy');

    Route::get('/tutorial-nodes/tree', [TutorialNodeController::class, 'tree'])->name('tutorial-nodes.tree');

    Route::apiResource('applications', ApplicationController::class)->whereNumber('application');
    Route::post('/tutorial-nodes/copy', [TutorialNodeController::class, 'copy'])->name('tutorial-nodes.copy');
    Route::apiResource('tutorial-nodes', TutorialNodeController::class)->whereNumber('tutorial_node');

    Route::get('/tutorial-nodes/{tutorialNode}/content-blocks', [TutorialContentBlockController::class, 'index'])->whereNumber('tutorialNode')->name('tutorial-nodes.content-blocks.index');
Route::post('/tutorial-nodes/{tutorialNode}/content-blocks', [TutorialContentBlockController::class, 'store'])->whereNumber('tutorialNode')->name('tutorial-nodes.content-blocks.store');
Route::put('/tutorial-content-blocks/{tutorialContentBlock}', [TutorialContentBlockController::class, 'update'])->whereNumber('tutorialContentBlock')->name('tutorial-content-blocks.update');
Route::delete('/tutorial-content-blocks/{tutorialContentBlock}', [TutorialContentBlockController::class, 'destroy'])->whereNumber('tutorialContentBlock')->name('tutorial-content-blocks.destroy');
Route::put('/tutorial-nodes/{tutorialNode}/content-blocks/reorder', [TutorialContentBlockController::class, 'reorder'])->whereNumber('tutorialNode')->name('tutorial-nodes.content-blocks.reorder');

});

Route::get('/applications', [PublicApplicationController::class, 'index'])->name('api.applications.index');
Route::get('/public/materi', [PublicMateriController::class, 'index'])->name('api.public.materi.index');

Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');
Route::middleware('auth:sanctum')->get('/auth/me', [AuthController::class, 'me'])->name('api.auth.me');
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');