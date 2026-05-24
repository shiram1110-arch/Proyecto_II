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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidoUno' => 'required|string|max:255',
            'apellidoDos' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'required|string|max:20',
            'userName' => 'required|string|unique:usuarios,userName',
            'password' => 'required|string|min:4'
        ]);

        $authUser = $request->user();

        $idRol = 1;

        if ($authUser && $authUser->rol && $authUser->rol->nombre === "ROLE_ADMIN") {
            $idRol = 2;
        }

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellidoUno' => $request->apellidoUno,
            'apellidoDos' => $request->apellidoDos,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'userName' => $request->userName,
            'password' => bcrypt($request->password),
            'idRol' => $idRol
            
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente',
            'data' => $usuario->load('rol')
        ], 201);
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
            'email' => 'sometimes|email|unique:usuarios,email,' . $id,
            'telefono' => 'sometimes|string|max:20',
            'userName' => 'sometimes|string|unique:usuarios,userName,' . $id,
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