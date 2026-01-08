<?php

namespace App\Models;
require_once __DIR__ . '/BaseModel.php';

class Notifica extends BaseModel
{
    protected $table = 'notifica';
    protected $fillable = [
        'tipo',          // es. "ordine", "recensione", ecc.
        'impostazione'   // boolean: true = attiva, false = disattiva
    ];

    protected $casts = [
        'impostazione' => 'boolean',
    ];
}