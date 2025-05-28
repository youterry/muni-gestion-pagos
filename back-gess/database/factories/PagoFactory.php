<?php

namespace Database\Factories;

use App\Models\Pago;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pago>
 */
class PagoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pago::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Obtener un ID de usuario existente aleatorio.
        // Si no hay usuarios en la DB, crea uno nuevo.
        $userId = User::inRandomOrder()->first()->id ?? User::factory()->create()->id;

        return [
            'user_id' => $userId,
            'monto' => $this->faker->randomFloat(2, 10, 2000), // Monto entre 10 y 2000
            // Genera fechas en un rango más amplio para pruebas de reportes
            'fecha' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'estado' => $this->faker->randomElement(['pendiente', 'completado', 'cancelado']),
        ];
    }
}