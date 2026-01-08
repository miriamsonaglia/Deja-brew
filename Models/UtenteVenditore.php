<?php
namespace App\Models;
require_once __DIR__ . '/BaseModel.php';

class UtenteVenditore extends BaseModel
{
    protected $table = 'utenteVenditore';
    protected $fillable = [
        'id_utente',
        'descrizione',
        'paese',                 // Nuovo
        'cellulare'              // Nuovo
    ];

    public function user()
    {
        return $this->belongsTo(Utente::class, 'id_utente');
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