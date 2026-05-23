<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReservaController extends Controller
{
    public function index(): JsonResponse
    {
        $reservas = Reserva::with('usuario', 'clase')->get();

        return response()->json([
            'success' => true,
            'data' => $reservas,
            'message' => 'Listado de reservas'
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'idUsuario'     => 'required|integer|exists:usuarios,idUsuario',
            'idClase'       => 'required|integer|exists:clases,idClase',
            'fechaReserva'  => 'required|date',
            'estado'        => 'required|string|max:50'
        ]);

        $reserva = Reserva::create($validated);

        return response()->json([
            'success' => true,
            'data' => $reserva,
            'message' => 'Reserva creada correctamente'
        ], 201);
    }

    public function show(Reserva $reserva): JsonResponse
    {
        $reserva->load('usuario', 'clase');

        return response()->json([
            'success' => true,
            'data' => $reserva,
            'message' => 'Reserva obtenida correctamente'
        ]);
    }

    public function update(Request $request, Reserva $reserva): JsonResponse
    {
        $validated = $request->validate([
            'idUsuario'     => 'sometimes|required|integer|exists:usuarios,idUsuario',
            'idClase'       => 'sometimes|required|integer|exists:clases,idClase',
            'fechaReserva'  => 'sometimes|required|date',
            'estado'        => 'sometimes|required|string|max:50'
        ]);

        $reserva->update($validated);

        return response()->json([
            'success' => true,
            'data' => $reserva,
            'message' => 'Reserva actualizada correctamente'
        ]);
    }

    public function destroy(Reserva $reserva): JsonResponse
    {
        $reserva->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reserva eliminada correctamente'
        ]);
    }
}