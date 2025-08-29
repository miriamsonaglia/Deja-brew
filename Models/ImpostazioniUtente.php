<?php

namespace App\Models;
require_once "./Models/BaseModel.php";

class ImpostazioniUtente extends BaseModel
{
    protected $table = 'impostazioniUtente';
    //con tema si intende modalità scura, quindi ad 1 sarà attiva, invece con 0 ci si aspetta la visualizzazione normale
    protected $fillable = [
        'id_utente',
        'tema',
        'notifiche_mail',
        'notifiche_push'
    ];

    protected $casts = [
        'tema' => 'boolean',
        'notifiche_mail' => 'boolean',
        'notifiche_push' => 'boolean',
    ];

    public function utente()
    {
        return $this->belongsTo(Utente::class, 'id_utente');
    }
}
