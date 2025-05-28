<?php

namespace Database\Seeders;

use App\Models\Pago;
use App\Models\User;
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que haya una cantidad razonable de usuarios para asociar los pagos.
        // Si ya hay usuarios, no creará más. Si no hay, creará 20.
        if (User::count() < 20) {
            User::factory(20 - User::count())->create(); // Crea hasta llegar a 20 usuarios si es necesario
        }

        // Crea una cantidad más grande de pagos para tener buenos datos de reporte
        Pago::factory(500)->create(); // Crea 500 pagos de prueba
    }
}