<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(): JsonResponse
    {
        $usuarios = Usuario::with('rol', 'reservas')->get();

        return response()->json([
            'success' => true,
            'data' => $usuarios,
            'message' => 'Listado de usuarios'
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100',
            'apellidoUno' => 'required|string|max:100',
            'apellidoDos' => 'nullable|string|max:100',
            'email'       => 'required|email|max:150|unique:usuarios,email',
            'telefono'    => 'nullable|string|max:20',
            'userName'    => 'required|string|max:50|unique:usuarios,userName',
            'password'    => 'required|string|min:6',
            'idRol'       => 'required|integer|exists:roles,idRol'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $usuario = Usuario::create($validated);

        return response()->json([
            'success' => true,
            'data' => $usuario,
            'message' => 'Usuario creado correctamente'
        ], 201);
    }

    public function show(Usuario $usuario): JsonResponse
    {
        $usuario->load('rol', 'reservas');

        return response()->json([
            'success' => true,
            'data' => $usuario,
            'message' => 'Usuario obtenido correctamente'
        ]);
    }

    public function update(Request $request, Usuario $usuario): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => 'sometimes|required|string|max:100',
            'apellidoUno' => 'sometimes|required|string|max:100',
            'apellidoDos' => 'sometimes|nullable|string|max:100',
            'email'       => 'sometimes|required|email|max:150|unique:usuarios,email,' . $usuario->idUsuario . ',idUsuario',
            'telefono'    => 'sometimes|nullable|string|max:20',
            'userName'    => 'sometimes|required|string|max:50|unique:usuarios,userName,' . $usuario->idUsuario . ',idUsuario',
            'password'    => 'sometimes|required|string|min:6',
            'idRol'       => 'sometimes|required|integer|exists:roles,idRol'
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $usuario->update($validated);

        return response()->json([
            'success' => true,
            'data' => $usuario,
            'message' => 'Usuario actualizado correctamente'
        ]);
    }

    public function destroy(Usuario $usuario): JsonResponse
    {
        $usuario->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente'
        ]);
    }
}