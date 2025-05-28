<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('concept'); // Concepto del pago (ej. "Cuota de agua", "Impuesto predial")
            $table->decimal('amount', 10, 2); // Monto del pago, 10 dígitos en total, 2 decimales
            $table->date('payment_date'); // Fecha en que se realizó el pago
            $table->string('status')->default('pending'); // Estado del pago (pending, paid, cancelled)
            $table->string('payer_name')->nullable(); // Nombre de quien realiza el pago
            $table->string('reference_number')->nullable(); // Número de referencia o recibo
            // Puedes añadir más campos como:
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Si quieres relacionar pagos con usuarios
            // $table->text('description')->nullable();
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}