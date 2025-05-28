<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment; // Importa el modelo Payment
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Para validación

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all(); // Obtener todos los pagos
        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'concept' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'status' => 'required|string|in:pending,paid,cancelled', // Valida que sea uno de estos valores
            'payer_name' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255|unique:payments,reference_number', // Asegura que sea único
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422); // 422 Unprocessable Entity
        }

        $payment = Payment::create($request->all());
        return response()->json($payment, 201); // 201 Created
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'concept' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric|min:0',
            'payment_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|string|in:pending,paid,cancelled',
            'payer_name' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255|unique:payments,reference_number,' . $payment->id, // Excluir el propio ID
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $payment->update($request->all());
        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(null, 204); // 204 No Content
    }
}