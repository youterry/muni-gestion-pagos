<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- Importar para la regla 'Rule::in'

class StorePagoRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // Define aquí tu lógica de autorización.
        // Por ejemplo: return auth()->check(); para requerir autenticación.
        return true; // Dejar en true para permitir la validación por ahora.
    }

    /**
     * Obtener las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Reglas base que se aplican a la creación (POST)
        $rules = [
            'user_id' => ['required', 'exists:users,id'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'fecha' => ['required', 'date'],
            'estado' => ['required', Rule::in(['pendiente', 'completado', 'cancelado'])],
        ];

        // Aplica 'sometimes' a todas las reglas si el método es PUT o PATCH (para actualizaciones)
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            foreach ($rules as $field => $fieldRules) {
                if (is_array($fieldRules)) {
                    array_unshift($rules[$field], 'sometimes'); // Añade 'sometimes' al inicio del array
                } else {
                    $rules[$field] = 'sometimes|' . $fieldRules; // Pre-añade 'sometimes|' si es un string
                }
            }
        }

        return $rules;
    }

    /**
     * Mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'El ID de usuario es obligatorio.',
            'user_id.exists' => 'El usuario especificado no existe.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser numérico.',
            'monto.min' => 'El monto debe ser mayor a cero.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe tener un formato válido.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser "pendiente", "completado" o "cancelado".',
        ];
    }
}