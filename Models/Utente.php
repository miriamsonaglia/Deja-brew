<?php

namespace App\Models;
require_once __DIR__ . '/BaseModel.php';

class Utente extends BaseModel
{
    protected $table = 'utente';

    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'username',
        'password',
        'immagine_profilo'       // Già presente, ma confermato
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relazioni
    public function utenteCompratore()
    {
        return $this->hasOne(UtenteCompratore::class);
    }

    public function utenteVenditore()
    {
        return $this->hasOne(UtenteVenditore::class);
    }

    public function recensioni()
    {
        return $this->hasMany(Recensione::class, 'id_utente');
    }

    public function ordini()
    {
        return $this->hasMany(Ordine::class, 'id_utente');
    }

    public function impostazioniUtente()
    {
        return $this->hasOne(ImpostazioniUtente::class, 'id_utente');
    }

    public function carteDiCredito()
    {
        return $this->hasMany(CartaDiCredito::class, 'id_utente');
    }

    public function fatture()
    {
        return $this->hasMany(Fattura::class, 'id_utente');
    }
}
