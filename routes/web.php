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