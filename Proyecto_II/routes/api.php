<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Middleware\SetLocaleFromHeader;

Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/usuarios', [UsuarioController::class, 'store']);
Route::get('/usuarios/buscar/{username}', [UsuarioController::class, 'buscar']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/perfil', [AuthApiController::class, 'me']);

    Route::get('/usuarios', [UsuarioController::class, 'index']);
    Route::post('/usuarios/admin', [UsuarioController::class, 'storeAdmin']);
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show']);
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update']);
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy']);

    Route::apiResource('clases', ClaseController::class);

    Route::middleware(SetLocaleFromHeader::class)->group(function () {
        Route::get('/reservas/mis-clases', [ReservaController::class, 'misClases']);
        Route::get('/reservas/estado/{estado}', [ReservaController::class, 'filtrarEstado']);
        Route::put('/reservas/cancelar/{id}', [ReservaController::class, 'cancelar']);
        Route::apiResource('reservas', ReservaController::class);
    });
});
