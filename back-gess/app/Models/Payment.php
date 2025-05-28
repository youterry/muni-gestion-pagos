<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'concept',
        'amount',
        'payment_date',
        'status',
        'payer_name',
        'reference_number',
        // Añade aquí todos los campos que definiste en tu migración
    ];
}