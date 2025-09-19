<?php

use Illuminate\Support\Facades\Route;

// Define your actual app routes here
Route::get('/', function () {
    return view('app');
});

Route::get('/portfolio', function () { return view('app'); });

Route::fallback(function () {
    abort(404);
});

