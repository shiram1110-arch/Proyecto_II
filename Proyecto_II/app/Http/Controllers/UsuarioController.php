<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Usuario::with('rol')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidoUno' => 'required|string|max:255',
            'apellidoDos' => 'nullable|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'nullable|string|max:20',
            'userName' => 'required|string|unique:usuarios,userName',
            'password' => 'required|string|min:4'
        ]);

        $usuario = $this->crearUsuario($validated, 2);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'data' => $usuario->load('rol')
        ], 201);
    }

    public function storeAdmin(Request $request)
    {
        if ($request->user()?->rol?->idRol !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos de administrador'
            ], 403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidoUno' => 'required|string|max:255',
            'apellidoDos' => 'nullable|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'nullable|string|max:20',
            'userName' => 'required|string|unique:usuarios,userName',
            'password' => 'required|string|min:4'
        ]);

        $usuario = $this->crearUsuario($validated, 1);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'data' => $usuario->load('rol')
        ], 201);
    }

    private function crearUsuario(array $data, int $idRol): Usuario
    {
        return Usuario::create([
            'nombre' => $data['nombre'],
            'apellidoUno' => $data['apellidoUno'],
            'apellidoDos' => $data['apellidoDos'] ?? null,
            'email' => $data['email'],
            'telefono' => $data['telefono'] ?? null,
            'userName' => $data['userName'],
            'password' => bcrypt($data['password']),
            'idRol' => $idRol
        ]);
    }

    public function show(string $id)
    {
        return response()->json([
            'success' => true,
            'data' => Usuario::with('rol')->findOrFail($id)
        ]);
    }

    public function update(Request $request, string $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'apellidoUno' => 'sometimes|string|max:255',
            'apellidoDos' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:usuarios,email,' . $id . ',idUsuario',
            'telefono' => 'sometimes|string|max:20',
            'userName' => 'sometimes|string|unique:usuarios,userName,' . $id . ',idUsuario',
            'password' => 'nullable|string|min:4',
            'idRol' => 'sometimes|integer'
        ]);

        $data = [
            'nombre' => $request->nombre,
            'apellidoUno' => $request->apellidoUno,
            'apellidoDos' => $request->apellidoDos,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'userName' => $request->userName,
            'idRol' => $request->idRol
        ];


        $data = array_filter($data, fn($value) => $value !== null);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $usuario->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente',
            'data' => $usuario->load('rol')
        ]);
    }

    public function destroy(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente'
        ]);
    }

    public function buscar($username)
    {
        return response()->json(
            Usuario::where('userName', 'LIKE', "%{$username}%")->get()
        );
    }
}
