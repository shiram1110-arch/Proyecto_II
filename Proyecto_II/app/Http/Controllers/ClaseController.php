<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    // Obtener todas las clases
    public function index()
    {
        return Clase::all();
    }

    // Obtener clase por ID
    public function show($id)
    {
        $clase = Clase::find($id);

        if (!$clase) {
            return response()->json([
                'mensaje' => 'Clase no encontrada'
            ], 404);
        }

        return $clase;
    }

    // Crear clase
    public function store(Request $request)
    {
        $clase = Clase::create($request->all());

        return response()->json($clase, 201);
    }

    // Actualizar clase
    public function update(Request $request, $id)
    {
        $clase = Clase::find($id);

        if (!$clase) {
            return response()->json([
                'mensaje' => 'Clase no encontrada'
            ], 404);
        }

        $clase->update($request->all());

        return response()->json($clase, 200);
    }

    // Eliminar clase
    public function destroy($id)
    {
        $clase = Clase::find($id);

        if (!$clase) {
            return response()->json([
                'mensaje' => 'Clase no encontrada'
            ], 404);
        }

        $clase->delete();

        return response()->json([
            'mensaje' => 'Clase eliminada'
        ], 200);
    }

    // Buscar clases por día
    public function getClasesPorDia($diaSemana)
    {
        return Clase::where('diaSemana', $diaSemana)->get();
    }

    // Buscar por nombre
    public function buscar($nombre)
    {
        return Clase::where('nombre', 'LIKE', "%$nombre%")->get();
    }
}