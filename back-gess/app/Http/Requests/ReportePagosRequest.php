<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- Importar Rule para 'estado'

class ReportePagosRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // Aquí puedes definir quién puede generar reportes.
        // Por ejemplo: return auth()->user()->hasRole('admin');
        return true; // Dejar en true por ahora para permitir el acceso.
    }

    /**
     * Obtener las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fecha_inicio' => ['required', 'date_format:Y-m-d'],
            'fecha_fin' => ['required', 'date_format:Y-m-d', 'after_or_equal:fecha_inicio'],
            'user_id' => ['nullable', 'exists:users,id'], // Opcional y debe existir en la tabla users
            'estado' => ['nullable', Rule::in(['pendiente', 'completado', 'cancelado'])], // Opcional y debe ser uno de los valores ENUM
            'per_page' => ['nullable', 'integer', 'min:1'], // Para paginación del reporte
        ];
    }

    /**
     * Mensajes de error personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date_format' => 'La fecha de inicio debe tener el formato YYYY-MM-DD.',
            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date_format' => 'La fecha de fin debe tener el formato YYYY-MM-DD.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'user_id.exists' => 'El ID de usuario especificado no existe.',
            'estado.in' => 'El estado debe ser "pendiente", "completado" o "cancelado".',
            'per_page.integer' => 'El parámetro per_page debe ser un número entero.',
            'per_page.min' => 'El parámetro per_page debe ser al menos 1.',
        ];
    }
}