<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Opcional: crea un usuario específico para pruebas, si no existe.
        // Puedes comentarlo si solo quieres usuarios aleatorios del seeder.
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }


        // Llama a los seeders de tus modelos
        $this->call([
            // UserSeeder::class, // Si tuvieras un UserSeeder separado para crear muchos usuarios
            PagoSeeder::class, // Llama a tu seeder de pagos
            // CursoSeeder::class, // Si tienes un seeder para cursos
        ]);
    }
}