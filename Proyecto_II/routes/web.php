<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/adminDashboard', 'admin.dashboard');

Route::view('/clasesVista', 'clases.index');

Route::view('/crearClase', 'clases.crear');

Route::view('/inicio', 'inicio');

Route::view('/', 'inicio');
