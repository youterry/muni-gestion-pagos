<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la aplicación.
     */
    public function run(): void
    {
        // Crea un usuario de prueba predeterminado para facilitar el inicio.
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Llama a otros seeders para poblar la base de datos.
        // Asegúrate de que PagoSeeder se ejecute después de que haya usuarios disponibles.
        $this->call(PagoSeeder::class);

        // Si tienes otros seeders, como para cursos, los puedes llamar aquí:
        // $this->call(CursoSeeder::class);
    }
}