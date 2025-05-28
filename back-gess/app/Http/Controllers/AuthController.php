<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Importa tu modelo User
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Maneja el intento de login del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Intentar autenticar al usuario
        if (!Auth::attempt($request->only('email', 'password'))) {
            // Si la autenticación falla, lanzar una excepción de validación
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Si la autenticación es exitosa, obtener el usuario autenticado
        $user = $request->user();

        // Crear un token de API para el usuario
        // 'auth_token' es el nombre del token (puedes cambiarlo)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user->only('id', 'name', 'email'), // Devuelve solo los datos necesarios del usuario
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Cierra la sesión del usuario (revoca el token actual).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Revocar el token actual del usuario autenticado
        // Esto elimina el token de la tabla personal_access_tokens
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    /**
     * Obtiene la información del usuario autenticado.
     * Este endpoint estará protegido por el middleware 'auth:sanctum'.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        // El usuario ya está autenticado gracias al middleware 'auth:sanctum'
        return response()->json($request->user()->only('id', 'name', 'email'));
    }
}