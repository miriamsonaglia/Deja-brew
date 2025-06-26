<?php

namespace App\Models;

class UtenteVenditore extends Model
{
    use HasFactory;

    protected $table = 'utenti_venditori';
    protected $fillable = ['user_id', 'descrizione'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class, 'id_venditore');
    }

    public function fatture()
    {
        return $this->hasMany(Fattura::class, 'id_venditore');
    }
}
