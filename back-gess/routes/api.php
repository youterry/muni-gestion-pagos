<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PagoReportController; // <-- Importar el nuevo controlador de reportes
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::apiResource('/cursos', CursoController::class)->middleware([HandlePrecognitiveRequests::class]);

// Rutas RESTful para Pagos (CRUD)
Route::apiResource('/pagos', PagoController::class);

// ******* NUEVA RUTA PARA REPORTES DE PAGOS *******
// Esta ruta se encargará de generar los reportes
Route::get('/pagos/reporte', [PagoReportController::class, 'generarReporte']);

// Ejemplo de cómo proteger todas estas rutas con autenticación Sanctum si es necesario:
/*
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/pagos', PagoController::class);
    Route::get('/pagos/reporte', [PagoReportController::class, 'generarReporte']);
});
*/