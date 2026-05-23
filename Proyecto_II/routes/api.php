<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Models\Usuario;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ReservaController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/login', function (Request $request) {

    $request->validate([
        'userName' => 'required|string',
        'password' => 'required|string'
    ]);

    // ✔️ MODELO CORRECTO
    $user = Usuario::where('userName', $request->userName)->first();

    // ✔️ VALIDACIÓN CORRECTA
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Credenciales inválidas'
        ], 401);
    }

    // ✔️ TOKEN SANCTUM
    $token = $user->createToken('token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
});


Route::post('/logout', function (Request $request) {

    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Sesión cerrada'
    ]);
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/perfil', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| API PROTEGIDA
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // USUARIOS
    Route::apiResource('usuarios', UsuarioController::class);

    // CLASES
    Route::apiResource('clases', ClaseController::class);

    Route::get('clases/dia/{diaSemana}', [ClaseController::class, 'getClasesPorDia']);
    Route::get('clases/buscar/{nombre}', [ClaseController::class, 'buscar']);

    // RESERVAS
    Route::apiResource('reservas', ReservaController::class);

    Route::get('reservas/estado/{estado}', [ReservaController::class, 'getByEstado']);
    Route::put('reservas/cancelar/{id}', [ReservaController::class, 'cancelar']);
});