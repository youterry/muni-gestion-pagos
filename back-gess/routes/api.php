<?php

use App\Http\Controllers\CursoController; // Suponiendo que ya tienes este controlador
use App\Http\Controllers\PagoController; // <-- Importar tu PagoController
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests; // Si usas Livewire o precognition
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta de usuario autenticado (típica en Laravel API)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Ejemplo de rutas para Cursos (si ya las tienes)
Route::apiResource('/cursos', CursoController::class)->middleware([HandlePrecognitiveRequests::class]);

// ******* RUTAS PARA PAGOS *******
// Define las rutas RESTful estándar (index, store, show, update, destroy) para PagoController.
Route::apiResource('/pagos', PagoController::class);

// Si necesitas que estas rutas de pagos estén protegidas por autenticación:
/*
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/pagos', PagoController::class);
    // Agrega aquí otras rutas que requieran autenticación
});
*/