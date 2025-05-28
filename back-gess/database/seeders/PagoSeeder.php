<?php

namespace Database\Seeders;

use App\Models\Pago;
use App\Models\User; // <-- Importar el modelo User
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    /**
     * Ejecuta las semillas de la base de datos.
     */
    public function run(): void
    {
        // Asegúrate de que haya al menos algunos usuarios antes de crear pagos.
        if (User::count() === 0) {
            User::factory(10)->create(); // Crea 10 usuarios si no hay ninguno.
        }

        // Crea 100 pagos utilizando el PagoFactory.
        Pago::factory(100)->create();
    }
}