<?php

namespace App\Models;

class ImpostazioniUtente extends BaseModel
{
    protected $table = 'impostazioni_utenti';
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
        return $this->belongsTo(User::class, 'id_utente');
    }
}
