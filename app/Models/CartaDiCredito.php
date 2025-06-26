<?php

namespace App\Models;

class CartaDiCredito extends BaseModel
{
    protected $table = 'cartaDiCredito';
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
        return $this->belongsTo(Utente::class, 'id_utente');
    }
}
