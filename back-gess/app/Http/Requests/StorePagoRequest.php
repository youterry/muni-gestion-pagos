<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePagoRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtener las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Las reglas ahora esperan los campos directamente en el request body.
        // Ejemplo: { "user_id": 1, "monto": 100.00, ... }
        $rules = [
            'user_id' => ['required', 'exists:users,id'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'fecha' => ['required', 'date'],
            'estado' => ['required', Rule::in(['pendiente', 'completado', 'cancelado'])],
        ];

        // Aplica 'sometimes' para PUT/PATCH para permitir actualizaciones parciales
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            foreach ($rules as $field => $fieldRules) {
                if (is_array($fieldRules)) {
                    array_unshift($rules[$field], 'sometimes');
                } else {
                    $rules[$field] = 'sometimes|' . $fieldRules;
                }
            }
        }

        return $rules;
    }

    // Los mensajes personalizados también deben adaptarse a la estructura no anidada
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