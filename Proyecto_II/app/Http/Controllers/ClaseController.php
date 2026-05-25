<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClaseController extends Controller
{
    public function index(): JsonResponse
    {
        $clases = Clase::with('reservas')->get();

        return response()->json([
            'success' => true,
            'data' => $clases,
            'message' => 'Listado de clases'
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'diaSemana'   => 'required|string|max:20',
            'horario'     => 'required|date_format:H:i',
            'capacidad'   => 'required|integer|min:1'
        ]);

        if ($this->existeClaseEnHorario($validated['diaSemana'], $validated['horario'])) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una clase programada para ese dia y hora'
            ], 422);
        }

        $clase = Clase::create($validated);

        return response()->json([
            'success' => true,
            'data' => $clase,
            'message' => 'Clase creada correctamente'
        ], 201);
    }

    public function show(Clase $clase): JsonResponse
    {
        $clase->load('reservas');

        return response()->json([
            'success' => true,
            'data' => $clase,
            'message' => 'Clase obtenida correctamente'
        ]);
    }

    public function update(Request $request, Clase $clase): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => 'sometimes|required|string|max:100',
            'descripcion' => 'sometimes|nullable|string|max:255',
            'diaSemana'   => 'sometimes|required|string|max:20',
            'horario'     => 'sometimes|required|date_format:H:i',
            'capacidad'   => 'sometimes|required|integer|min:1'
        ]);

        $diaSemana = $validated['diaSemana'] ?? $clase->diaSemana;
        $horario = $validated['horario'] ?? $clase->horario->format('H:i');

        if ($this->existeClaseEnHorario($diaSemana, $horario, $clase->idClase)) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una clase programada para ese dia y hora'
            ], 422);
        }

        $clase->update($validated);

        return response()->json([
            'success' => true,
            'data' => $clase,
            'message' => 'Clase actualizada correctamente'
        ]);
    }

    public function destroy(Clase $clase): JsonResponse
    {
        $clase->delete();

        return response()->json([
            'success' => true,
            'message' => 'Clase eliminada correctamente'
        ]);
    }

    private function existeClaseEnHorario(string $diaSemana, string $horario, ?int $exceptoIdClase = null): bool
    {
        $query = Clase::where('diaSemana', $diaSemana)
            ->where('horario', $horario);

        if ($exceptoIdClase) {
            $query->where('idClase', '!=', $exceptoIdClase);
        }

        return $query->exists();
    }
}
