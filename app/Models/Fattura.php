<?php

namespace App\Models;

class Fattura extends Model
{
    use HasFactory;

    protected $table = 'fatture';
    protected $fillable = [
        'id_utente',
        'id_venditore',
        'id_ordine'
    ];

    public function utente()
    {
        return $this->belongsTo(User::class, 'id_utente');
    }

    public function venditore()
    {
        return $this->belongsTo(UtenteVenditore::class, 'id_venditore');
    }

    public function ordine()
    {
        return $this->belongsTo(Ordine::class, 'id_ordine');
    }
}
