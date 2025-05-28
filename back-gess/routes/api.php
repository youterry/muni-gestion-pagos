<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PagoReportController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta de prueba (según tu imagen, '/api/test' ya funciona)
Route::get('/test', function () {
    return response()->json(['message' => '¡API Funciona!']);
});

// Esta ruta ya no estará protegida por Sanctum, si la tenías así antes.
// Si necesitas alguna autenticación, será la de sesión web por defecto de Laravel,
// o tendrás que protegerla con un middleware personalizado.
Route::get('/user', function (Request $request) {
    return $request->user();
});
// Si tu versión anterior no tenía el middleware('auth:api'), déjalo así.

Route::apiResource('/cursos', CursoController::class)->middleware([HandlePrecognitiveRequests::class]);

// Rutas RESTful para Pagos (CRUD)
Route::apiResource('/pagos', PagoController::class);

// Ruta para reportes de pagos
Route::get('/pagos/reporte', [PagoReportController::class, 'generarReporte']);

// Elimina cualquier bloque Route::middleware('auth:sanctum')->group(...)