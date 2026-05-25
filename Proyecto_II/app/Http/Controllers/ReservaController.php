<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Clase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReservaController extends Controller
{
    public function index(): JsonResponse
    {
        if (!$this->isAdmin(request())) {
            return $this->forbidden();
        }

        $reservas = Reserva::with('usuario', 'clase')->get();

        return response()->json([
            'success' => true,
            'data' => $reservas,
            'message' => __('messages.reservation_list')
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'idClase'       => 'required|integer|exists:clases,idClase',
            'fechaReserva'  => 'nullable|date',
            'estado'        => 'nullable|in:ACTIVA'
        ]);

        $usuario = $request->user();

        if (!$usuario) {
            return $this->forbidden();
        }

        $clase = Clase::findOrFail($validated['idClase']);

        if ($clase->capacidad <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('messages.reservation_full')
            ], 400);
        }

        $existe = Reserva::where('idUsuario', $usuario->idUsuario)
            ->where('idClase', $validated['idClase'])
            ->where('estado', 'ACTIVA')
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => __('messages.reservation_duplicate')
            ], 400);
        }

        $validated['idUsuario'] = $usuario->idUsuario;
        $validated['fechaReserva'] = $validated['fechaReserva'] ?? now()->toDateString();
        $validated['estado'] = 'ACTIVA';

        $reserva = Reserva::create($validated);

        $clase->capacidad -= 1;
        $clase->save();

        return response()->json([
            'success' => true,
            'data' => $reserva,
            'message' => __('messages.reservation_created')
        ], 201);
    }

    public function show(Reserva $reserva): JsonResponse
    {
        if (!$this->canManageReserva(request(), $reserva)) {
            return $this->forbidden();
        }

        $reserva->load('usuario', 'clase');

        return response()->json([
            'success' => true,
            'data' => $reserva,
            'message' => __('messages.reservation_obtained')
        ]);
    }

    public function update(Request $request, Reserva $reserva): JsonResponse
    {
        if (!$this->isAdmin($request)) {
            return $this->forbidden();
        }

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
            'message' => __('messages.reservation_updated')
        ]);
    }

    public function misClases(Request $request): JsonResponse
    {
        $reservas = Reserva::with('clase')
            ->where('idUsuario', $request->user()->idUsuario)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reservas,
            'message' => __('messages.reservation_user_list')
        ]);
    }

    public function filtrarEstado(string $estado): JsonResponse
    {
        if (!$this->isAdmin(request())) {
            return $this->forbidden();
        }

        $reservas = Reserva::with('usuario', 'clase')
            ->where('estado', $estado)
            ->get()
            ->map(fn ($reserva) => [
                'idReserva' => $reserva->idReserva,
                'nombreUsuario' => $reserva->usuario?->nombre,
                'nombreClase' => $reserva->clase?->nombre,
                'fechaReserva' => optional($reserva->fechaReserva)->format('Y-m-d'),
                'estado' => $reserva->estado,
            ]);

        return response()->json([
            'success' => true,
            'data' => $reservas
        ]);
    }

    public function cancelar(string $id): JsonResponse
    {
        $reserva = Reserva::with('clase')->findOrFail($id);

        if (!$this->canManageReserva(request(), $reserva)) {
            return $this->forbidden();
        }

        if ($reserva->estado === 'CANCELADA') {
            return response()->json([
                'success' => false,
                'message' => __('messages.reservation_already_cancelled')
            ], 400);
        }

        $estabaActiva = $reserva->estado === 'ACTIVA';

        $reserva->update([
            'estado' => 'CANCELADA'
        ]);

        if ($estabaActiva && $reserva->clase) {
            $reserva->clase->capacidad += 1;
            $reserva->clase->save();
        }

        return response()->json([
            'success' => true,
            'data' => $reserva,
            'message' => __('messages.reservation_cancelled')
        ]);
    }

    public function destroy(Reserva $reserva): JsonResponse
    {
        if (!$this->canManageReserva(request(), $reserva)) {
            return $this->forbidden();
        }

        $reserva->loadMissing('clase');

        if ($reserva->estado === 'ACTIVA' && $reserva->clase) {
            $reserva->clase->capacidad += 1;
            $reserva->clase->save();
        }

        $reserva->delete();

        return response()->json([
            'success' => true,
            'message' => __('messages.reservation_deleted')
        ]);
    }

    private function isAdmin(Request $request): bool
    {
        return $request->user()?->rol?->idRol === 1;
    }

    private function canManageReserva(Request $request, Reserva $reserva): bool
    {
        $usuario = $request->user();

        return $usuario && (
            $usuario->rol?->idRol === 1 ||
            $reserva->idUsuario === $usuario->idUsuario
        );
    }

    private function forbidden(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => __('messages.forbidden')
        ], 403);
    }
}
