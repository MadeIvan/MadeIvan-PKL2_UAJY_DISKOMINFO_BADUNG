<?php

use App\Http\Controllers\PublicApplicationPageController;
use App\Http\Controllers\TutorialContentPageController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
| Public Pages
*/

Route::view('/', 'welcome')->name('home');
Route::view('/content', 'content_page')->name('content');
Route::view('/app-list', 'app_index')->name('applications.list');
Route::view('/applications-demo', 'materi.showApp')->name('applications.index');

/*
| Public Application Documentation
*/

Route::get('/applications/{application:slug}', [PublicApplicationPageController::class, 'show'])->name('applications.show');

/*
| Public Material
*/

Route::get('/materi/{tutorialNode:slug}', [TutorialContentPageController::class, 'publicShow'])->name('materi.Materi');

/*
| Admin Pages
*/

Route::view('/admin/login', 'Admin.login')->name('admin.login');
Route::view('/admin/input', 'Admin.input_content')->name('admin.input');
Route::view('/admin/content-index', 'Admin.content_index')->name('admin.content-index');
Route::get('/admin', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
Route::view('/admin/add-app', 'Admin.add_app')->name('admin.applications.create');
Route::view('/admin/categories', 'Admin.categories.index')->name('admin.categories.index');
Route::view('/admin/materi', 'Admin.materi.index')->name('admin.materi.index');
Route::view('/admin/aplikasi', 'Admin.applications.index')->name('admin.applications.index');

/*
| Admin Material Content
*/

Route::get('/admin/materi/{tutorialNode}/content', [TutorialContentPageController::class, 'edit'])->whereNumber('tutorialNode')->name('admin.materi.content');

Route::get('/admin/materi/{tutorialNode}/preview', [TutorialContentPageController::class, 'preview'])->whereNumber('tutorialNode')->name('admin.materi.preview');

/*
| Development Utilities
*/

Route::get('/db-check', function (): array {
    return [
        'host' => DB::selectOne('SELECT @@hostname AS host')->host,
        'database' => DB::connection()->getDatabaseName(),
        'sessions_exists' => Schema::hasTable('sessions'),
        'tables' => Schema::getTableListing(),
    ];
})->name('development.db-check');