<?php

namespace App\Models;

class UtenteCompratore extends BaseModel
{
    protected $table = 'utenti_compratori';
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
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

?>