<?php

use App\Http\Controllers\TutorialContentPageController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('home');
Route::view('/content', 'content_page')->name('content');
Route::view('/app-list', 'app_index')->name('applications.list');
Route::view('/applications-demo', 'Public_View.showApp')->name('applications.index');

/*
|--------------------------------------------------------------------------
| Public Material
|--------------------------------------------------------------------------
*/

Route::get('/materi/{tutorialNode:slug}', [TutorialContentPageController::class, 'publicShow'])->name('materi.Materi');

/*
|--------------------------------------------------------------------------
| Admin Pages
|--------------------------------------------------------------------------
*/

Route::view('/admin/input', 'Admin.input_content')->name('admin.input');
Route::view('/admin/content-index', 'Admin.content_index')->name('admin.content-index');
Route::view('/admin/admin-dashboard', 'Admin.admin_dashboard')->name('admin.dashboard');
Route::view('/admin/add-app', 'Admin.add_app')->name('admin.applications.create');
Route::view('/admin/category/index', 'Admin.category_index')->name('admin.categories.index');
Route::view('/admin/Materi-demo', 'Admin.materi-demo.index')->name('admin.materi-demo.index');
Route::view('/admin/aplikasi-demo', 'Admin.applications.index')->name('admin.applications.index');

/*
|--------------------------------------------------------------------------
| Admin Material Content
|--------------------------------------------------------------------------
*/

Route::get('/admin/Materi-demo/{tutorialNode}/content', [TutorialContentPageController::class, 'edit'])->whereNumber('tutorialNode')->name('admin.materi-demo.content');
Route::get('/admin/Materi-demo/{tutorialNode}/preview', [TutorialContentPageController::class, 'preview'])->whereNumber('tutorialNode')->name('admin.materi-demo.preview');

/*
|--------------------------------------------------------------------------
| Development Utilities
|--------------------------------------------------------------------------
|
| Hapus route ini ketika aplikasi sudah masuk production.
|
*/

Route::get('/db-check', function (): array {
    return [
        'host' => DB::selectOne('SELECT @@hostname AS host')->host,
        'database' => DB::connection()->getDatabaseName(),
        'sessions_exists' => Schema::hasTable('sessions'),
        'tables' => Schema::getTableListing(),
    ];
})->name('development.db-check');