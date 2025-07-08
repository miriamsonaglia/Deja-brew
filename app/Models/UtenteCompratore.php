<?php

namespace App\Models;

class UtenteCompratore extends BaseModel
{
    protected $table = 'utenteCompratore';
    protected $fillable = ['id_utente'];

    public function user()
    {
        return $this->belongsTo(Utente::class);
    }

    public function liste()
    {
        return $this->hasMany(Lista::class, 'id_utente_compratore');
    }

    public function desideri()
    {
        return $this->hasMany(Lista::class, 'id_utente_compratore')->where('tipo', 'desideri');
    }

    public function carrello()
    {
        return $this->hasMany(Lista::class, 'id_utente_compratore')->where('tipo', 'carrello');
    }
}