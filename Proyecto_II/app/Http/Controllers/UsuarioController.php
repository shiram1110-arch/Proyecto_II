<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // Obtener todos los usuarios
    public function index()
    {
        return Usuario::with('rol')->get();
    }

    // Obtener usuario por ID
    public function show($id)
    {
        $usuario = Usuario::with('rol')->find($id);

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no encontrado'
            ], 404);
        }

        return $usuario;
    }

    // Crear usuario
    public function store(Request $request)
    {
        $usuario = Usuario::create($request->all());

        return response()->json($usuario, 201);
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no encontrado'
            ], 404);
        }

        $usuario->update($request->all());

        return response()->json($usuario, 200);
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no encontrado'
            ], 404);
        }

        $usuario->delete();

        return response()->json([
            'mensaje' => 'Usuario eliminado'
        ], 200);
    }
}