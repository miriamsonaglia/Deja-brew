<?php

namespace App\Models;
require_once "./Models/BaseModel.php";

class ImpostazioniUtente extends BaseModel
{
    protected $table = 'impostazioniUtente';
    protected $fillable = [
        'id_utente',
        'tema',
        'notifiche'
    ];

    protected $casts = [
        'notifiche' => 'boolean',
    ];

    public function utente()
    {
        return $this->belongsTo(Utente::class, 'id_utente');
    }
}
