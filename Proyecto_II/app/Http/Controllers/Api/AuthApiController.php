<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'userName' => 'required',
            'password' => 'required'
        ]);

        $usuario = Usuario::with('rol')
            ->where('userName', $request->userName)
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {

            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'usuario' => $usuario
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout correcto'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'data' => $request->user()->load('rol')
        ]);
    }
}
