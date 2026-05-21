<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ReservaController;

Route::apiResource('usuarios', UsuarioController::class);

Route::apiResource('clases', ClaseController::class);

Route::get(
    'clases/dia/{diaSemana}',
    [ClaseController::class, 'getClasesPorDia']
);

Route::get(
    'clases/buscar/{nombre}',
    [ClaseController::class, 'buscar']
);

Route::apiResource('reservas', ReservaController::class);

Route::get(
    'reservas/estado/{estado}',
    [ReservaController::class, 'getByEstado']
);

Route::put(
    'reservas/cancelar/{id}',
    [ReservaController::class, 'cancelar']
);