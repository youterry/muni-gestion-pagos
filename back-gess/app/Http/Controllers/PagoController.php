<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Http\Requests\StorePagoRequest; // Usamos este Request para store y update
use Illuminate\Http\Request;

class PagoController extends Controller
{
    /**
     * Mostrar una lista del recurso.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Retorna una lista paginada de pagos, cargando la relación 'user'.
        // Permite controlar la cantidad de elementos por página con el parámetro 'per_page'.
        return response()->json(Pago::with('user')->paginate($request->query('per_page', 50)));
    }

    /**
     * Almacenar un recurso recién creado en el almacenamiento.
     *
     * @param  \App\Http\Requests\StorePagoRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePagoRequest $request)
    {
        // La validación y autorización son manejadas automáticamente por StorePagoRequest.
        // Si la validación es exitosa, se crea el pago con los datos validados.
        $pago = Pago::create($request->validated());

        return response()->json($pago, 201); // 201 Created
    }

    /**
     * Mostrar el recurso especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        // Busca un pago por ID y carga la relación 'user'.
        // findOrFail() lanza una excepción 404 Not Found si el pago no existe.
        $pago = Pago::with('user')->findOrFail($id);

        return response()->json($pago);
    }

    /**
     * Actualizar el recurso especificado en el almacenamiento.
     *
     * @param  \App\Http\Requests\StorePagoRequest  $request
     * @param  \App\Models\Pago  $pago // Inyección de modelo implícita
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StorePagoRequest $request, Pago $pago)
    {
        // La validación se maneja por StorePagoRequest (incluyendo 'sometimes' para update).
        // El modelo $pago ya fue resuelto por Laravel (lanza 404 si no existe).
        $pago->update($request->validated());

        return response()->json($pago);
    }

    /**
     * Eliminar el recurso especificado del almacenamiento.
     *
     * @param  \App\Models\Pago  $pago // Inyección de modelo implícita
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Pago $pago)
    {
        // El modelo $pago ya fue resuelto por Laravel (lanza 404 si no existe).
        $pago->delete();

        return response()->json(['message' => 'Pago eliminado correctamente'], 200); // 200 OK
    }
    
}