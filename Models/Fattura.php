<?php

namespace App\Models;
require_once "./Models/BaseModel.php";

class Fattura extends BaseModel
{
    protected $table = 'fattura';
    protected $fillable = [
        'id_utente',
        'id_venditore',
        'id_ordine'
    ];

    public function utente()
    {
        return $this->belongsTo(Utente::class, 'id_utente');
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
