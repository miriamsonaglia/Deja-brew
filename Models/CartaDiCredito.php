<?php

namespace App\Models;

require_once __DIR__ . '/BaseModel.php';

class CartaDiCredito extends BaseModel
{
    protected $table = 'cartaDiCredito';

    protected $fillable = [
        'id_utente',
        'circuito_pagamento',
        'codice_carta',
        'cvv_carta',
        'nome_titolare',         // Nuovo
        'scadenza_mese',
        'scadenza_anno'
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