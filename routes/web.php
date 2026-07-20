<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/content', function () {
    return view('content_page');
});
Route::get('/input', function () {
    return view('input_content');
});
Route::get('/content-index', function () {
    return view('content_index');
});

Route::get('/app_list', function () {
    return view('app_index');
});

Route::get('/admin_dashboard', function () {
    return view('admin_dashboard');
});

Route::get('/db-check', function () {
    return [
        'host' => DB::selectOne('SELECT @@hostname AS host')->host,
        'database' => DB::connection()->getDatabaseName(),
        'sessions_exists' => Schema::hasTable('sessions'),
        'tables' => Schema::getTableListing(),
    ];
});