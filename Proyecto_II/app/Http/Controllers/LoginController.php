<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'userName' => 'required|string',
            'password' => 'required|string'
        ]);

        $usuario = Usuario::with('rol') 
            ->where('userName', $request->userName)
            ->first();

        if (
            !$usuario ||
            !Hash::check($request->password, $usuario->password)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login correcto',
            'token' => $token,
            'user' => $usuario
        ]);
    }
}