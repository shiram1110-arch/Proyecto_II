<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Models\Usuario;
use App\Models\Reserva;
/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// =====================
// VISTAS GENERALES
// =====================

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/inicio', function () {
    return view('inicio'); // 🔥 CORREGIDO (antes usuarios.inicio)
})->name('inicio');


// =====================
// LOGIN Y AUTENTICACIÓN
// =====================

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::post('/registroCompleto/login', [LoginController::class, 'login']);


// =====================
// REGISTRO USUARIO
// =====================

Route::get('/registro', function () {
    return view('usuarios.formularioVikingNuevo');
})->name('registro');


// =====================
// ADMIN
// =====================

Route::get('/admin/adminDashboard', function () {
    return view('admin.adminDashboard');
})->name('adminDashboard');


// =====================
// CLASES
// =====================

Route::get('/clases/clasesVista', function () {
    return view('clases.clasesVista');
})->name('clasesVista');

Route::get('/clases/crear', function () {
    return view('clases.crearClase');
})->name('clases.crear');

use Illuminate\Http\Request;
use App\Models\Clase;

Route::get('/clases/horarioClases', function (Request $request) {

    $diaActual = $request->get('dia_semana', 'LUNES');

    $clases = Clase::where('diaSemana', $diaActual)->get();

    return view('clases.horarioClases', compact('diaActual', 'clases'));
});


// =====================
// RESERVAS
// =====================

Route::get('/reservas', function () {
    return view('reservas.reservas');
})->name('reservas');

Route::get('/reservas/gestionReservas', function () {

    $reservas = Reserva::with('usuario', 'clase')->get();

    return view('reservas.gestionReservas', compact('reservas'));
});

Route::get('/reservas/historial', function () {

    $reservas = \App\Models\Reserva::with('usuario','clase')
        ->get();

    return view('reservas.historial', compact('reservas'));
});

// =====================
// USUARIOS
// =====================

Route::get('/usuarios/inicio', function () {
    return view('inicio'); // 🔥 opcional: si lo quieres mantener también correcto
})->name('usuarios.inicio');

Route::get('/usuariosVista', function () {
    $usuarios = Usuario::all();

    return view('usuarios.usuariosVista', compact('usuarios'));
});


Route::get('/usuarios/{id}/editar', function ($id) {

    $usuario = Usuario::findOrFail($id);

    return view('usuarios.formularioVikingNuevo', compact('usuario'));
});

Route::get('/registro', function () {
    return view('usuarios.formularioVikingNuevo');
});