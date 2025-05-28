<?php

namespace Database\Factories;

use App\Models\Pago;
use App\Models\User; // <-- Importar el modelo User
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pago>
 */
class PagoFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente al factory.
     *
     * @var string
     */
    protected $model = Pago::class;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Asigna un user_id. Intenta usar un usuario existente al azar.
            // Si la tabla 'users' está vacía, creará un nuevo usuario.
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'monto' => $this->faker->randomFloat(2, 50, 5000), // Monto aleatorio entre 50.00 y 5000.00
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'), // Fecha en el último año
            'estado' => $this->faker->randomElement(['pendiente', 'completado', 'cancelado']), // Estado aleatorio
        ];
    }
}