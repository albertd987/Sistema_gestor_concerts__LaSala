<?php

use Illuminate\Support\Facades\Route;

// Ruta pública (home)
Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/slots', function () {
    return view('admin.slots');
});
