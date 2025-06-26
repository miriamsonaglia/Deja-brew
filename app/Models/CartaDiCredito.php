<?php

namespace App\Models;

class CartaDiCredito extends Model
{
    use HasFactory;

    protected $table = 'carte_di_credito';
    protected $fillable = [
        'id_utente',
        'circuito_pagamento',
        'codice_carta',
        'cvv_carta'
    ];

    protected $hidden = [
        'codice_carta',
        'cvv_carta'
    ];

    public function utente()
    {
        return $this->belongsTo(User::class, 'id_utente');
    }
}
