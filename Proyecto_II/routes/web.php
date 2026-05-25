<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController;
use App\Models\Usuario;
use App\Models\Reserva;


Route::get('/', function () {
    return view('inicio');
})->name('inicio');

Route::get('/inicio', function () {
    return view('inicio'); 
})->name('inicio');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::post('/registroCompleto/login', [LoginController::class, 'login']);


Route::get('/registro', function () {
    return view('usuarios.formularioVikingNuevo');
})->name('registro');


Route::get('/admin/adminDashboard', function () {
    return view('admin.adminDashboard');
})->name('adminDashboard');


Route::get('/clases/clasesVista', function () {
    return view('clases.clasesVista');
})->name('clasesVista');

Route::get('/clases/crear', function () {
    return view('clases.crearClase');
})->name('clases.crear');

use Illuminate\Http\Request;
use App\Models\Clase;

Route::get('/clases/editar/{id}', function ($id) {
    $clase = Clase::findOrFail($id);

    return view('clases.crearClase', compact('clase'));
});

Route::get('/clases/horarioClases', function (Request $request) {

    $diaActual = $request->get('dia_semana', 'LUNES');

    $clases = Clase::where('diaSemana', $diaActual)->get();

    return view('clases.horarioClases', compact('diaActual', 'clases'));
});


Route::get('/reservas/gestionReservas', function () {

    $reservas = collect();

    return view('reservas.gestionReservas', compact('reservas'));
});

Route::get('/reservas/historial', function () {

    $reservas = collect();
    $usuario = null;

    return view('reservas.historial', compact('reservas', 'usuario'));
});

Route::get('/reservas/{id}', function ($id) {

    $clase = Clase::find($id);

    return view('reservas.reservas', compact('clase'));
})->whereNumber('id')->name('reservas');


Route::get('/usuarios/inicio', function () {
    return view('inicio'); 
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
