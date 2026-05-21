<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Usuario;
use App\Models\Clase;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    // Obtener todas las reservas
    public function index()
    {
        return Reserva::with(['usuario', 'clase'])->get();
    }

    // Obtener reserva por ID
    public function show($id)
    {
        $reserva = Reserva::with(['usuario', 'clase'])->find($id);

        if (!$reserva) {
            return response()->json([
                'mensaje' => 'Reserva no encontrada'
            ], 404);
        }

        return $reserva;
    }

    // Crear reserva
    public function store(Request $request)
    {
        $usuario = Usuario::find($request->idUsuario);

        if (!$usuario) {
            return response()->json([
                'mensaje' => 'Usuario no encontrado'
            ], 404);
        }

        $clase = Clase::find($request->idClase);

        if (!$clase) {
            return response()->json([
                'mensaje' => 'Clase no encontrada'
            ], 404);
        }

        $reserva = Reserva::create([
            'idUsuario' => $usuario->idUsuario,
            'idClase' => $clase->idClase,
            'fechaReserva' => $request->fechaReserva,
            'estado' => $request->estado
        ]);

        return response()->json($reserva, 201);
    }

    // Actualizar reserva
    public function update(Request $request, $id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json([
                'mensaje' => 'Reserva no encontrada'
            ], 404);
        }

        $reserva->update($request->all());

        return response()->json($reserva, 200);
    }

    // Eliminar reserva
    public function destroy($id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json([
                'mensaje' => 'Reserva no encontrada'
            ], 404);
        }

        $reserva->delete();

        return response()->json([
            'mensaje' => 'Reserva eliminada'
        ], 200);
    }

    // Buscar reservas por estado
    public function getByEstado($estado)
    {
        return Reserva::with(['usuario', 'clase'])
            ->where('estado', $estado)
            ->get();
    }

    // Cancelar reserva
    public function cancelar($id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json([
                'mensaje' => 'Reserva no encontrada'
            ], 404);
        }

        $reserva->estado = 'Cancelada';

        $reserva->save();

        return response()->json($reserva, 200);
    }
}