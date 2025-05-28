<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PagoReportController;
use App\Http\Controllers\AuthController; // <--- ¡Importa tu AuthController!
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta de prueba (ya corregida para que sea solo '/test')
Route::get('/test', function () {
    return response()->json(['message' => '¡API Funciona!']);
});

// ***** RUTAS DE AUTENTICACIÓN PÚBLICAS (NO REQUIEREN TOKEN) *****
// El endpoint de login debe ser accesible sin autenticación
Route::post('/login', [AuthController::class, 'login']);

// ***** RUTAS PROTEGIDAS (REQUIEREN TOKEN DE AUTENTICACIÓN) *****
// Todas las rutas dentro de este grupo requerirán un encabezado 'Authorization: Bearer <TU_TOKEN>'
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de autenticación protegidas
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']); // Para obtener la información del usuario autenticado

    // Tus rutas de CRUD y Reportes de Pagos ahora están protegidas aquí
    // Esto significa que para acceder a estas rutas, el usuario debe estar autenticado
    Route::apiResource('/cursos', CursoController::class)->middleware([HandlePrecognitiveRequests::class]);
    Route::apiResource('/pagos', PagoController::class);
    Route::get('/pagos/reporte', [PagoReportController::class, 'generarReporte']);

    // Agrega cualquier otra ruta que deba estar protegida aquí
});