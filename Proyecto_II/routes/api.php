<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Api\AuthApiController;

Route::post('/login', [AuthApiController::class, 'login']);

Route::post('/usuarios',[UsuarioController::class,'store']);
Route::get('/usuarios/buscar/{username}',[UsuarioController::class,'buscar']);




Route::middleware('auth:sanctum')->group(function(){

Route::post('/logout',[AuthController::class,'logout']);
Route::get('/perfil',[AuthController::class,'me']);

Route::get('/usuarios',[UsuarioController::class,'index']);
Route::get('/usuarios/{usuario}',[UsuarioController::class,'show']);
Route::put('/usuarios/{usuario}',[UsuarioController::class,'update']);
Route::delete('/usuarios/{usuario}',[UsuarioController::class,'destroy']);

Route::apiResource('clases',ClaseController::class);

Route::get('/reservas/mis-clases',[ReservaController::class,'misClases']);
Route::get('/reservas/estado/{estado}',[ReservaController::class,'filtrarEstado']);
Route::put('/reservas/cancelar/{id}',[ReservaController::class,'cancelar']);

Route::apiResource('reservas',ReservaController::class);

});