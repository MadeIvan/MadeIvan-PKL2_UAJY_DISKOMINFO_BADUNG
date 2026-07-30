<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/content', function () {
    return view('content_page');
});
Route::get('admin/input', function () {
    return view('Admin.input_content');
});
Route::get('admin/content-index', function () {
    return view('Admin.content_index');
});

Route::get('app_list', function () {
    return view('app_index');
});

Route::get('admin/admin_dashboard', function () {
    return view('Admin.admin_dashboard');
});

Route::get('admin/add_app', function () {
    return view('Admin.add_app');
});

Route::get('admin/category/index', function () {
    return view('Admin.category_index');
});


Route::get('/db-check', function () {
    return [
        'host' => DB::selectOne('SELECT @@hostname AS host')->host,
        'database' => DB::connection()->getDatabaseName(),
        'sessions_exists' => Schema::hasTable('sessions'),
        'tables' => Schema::getTableListing(),
    ];
});
Route::view('/admin/Materi-demo','Admin.materi-demo.index')->name('Admin.materi-demo.index');

Route::view('/admin/applications-demo','Admin.applications.index')->name('Admin.applications.index');

Route::view('/applications-demo', 'Public_View.showApp')->name('applications.index');