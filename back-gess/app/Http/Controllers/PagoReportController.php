<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Http\Requests\ReportePagosRequest; // <-- Importar el Request para el reporte
use Illuminate\Http\JsonResponse; // <-- Importar JsonResponse para tipado
use Illuminate\Support\Facades\DB; // <-- Importar DB para agregaciones si fuera necesario

class PagoReportController extends Controller
{
    /**
     * Genera un reporte de pagos basado en los filtros proporcionados.
     *
     * @param  \App\Http\Requests\ReportePagosRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generarReporte(ReportePagosRequest $request): JsonResponse
    {
        // Los datos ya están validados por ReportePagosRequest
        $validatedData = $request->validated();

        $query = Pago::query()->with('user'); // Inicia la consulta y carga la relación 'user'

        // Aplica filtros condicionalmente
        if (isset($validatedData['fecha_inicio'])) {
            $query->whereDate('fecha', '>=', $validatedData['fecha_inicio']);
        }

        if (isset($validatedData['fecha_fin'])) {
            $query->whereDate('fecha', '<=', $validatedData['fecha_fin']);
        }

        if (isset($validatedData['user_id'])) {
            $query->where('user_id', $validatedData['user_id']);
        }

        if (isset($validatedData['estado'])) {
            $query->where('estado', $validatedData['estado']);
        }

        // Obtiene los pagos filtrados y los pagina
        $perPage = $validatedData['per_page'] ?? 50; // Usa 50 por defecto si no se especifica 'per_page'
        $pagos = $query->paginate($perPage);

        // Calcula el monto total de los pagos filtrados
        // Es más eficiente calcular el total en la base de datos para los resultados filtrados.
        // Si paginamos, el total_monto debería ser del conjunto completo, no solo de la página actual.
        $totalMonto = $query->sum('monto');
        $totalPagos = $query->count(); // Cantidad total de pagos filtrados

        return response()->json([
            'meta' => [ // Información de paginación
                'current_page' => $pagos->currentPage(),
                'last_page' => $pagos->lastPage(),
                'per_page' => $pagos->perPage(),
                'total' => $pagos->total(),
            ],
            'data' => $pagos->items(), // Los elementos de la página actual
            'report_summary' => [ // Resumen del reporte completo (sin paginar)
                'total_monto_filtrado' => (float) $totalMonto,
                'cantidad_pagos_filtrados' => (int) $totalPagos,
            ],
        ]);
    }
}