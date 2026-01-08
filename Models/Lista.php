<?php

namespace App\Models;
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Prodotto.php';
require_once __DIR__ . '/UtenteCompratore.php';

class Lista extends BaseModel
{
    protected $table = 'lista';
    protected $fillable = [
        'id_utente_compratore',
        'id_prodotto',
        'tipo', // 'desideri' o 'carrello'
        'quantita'
    ];

    protected $casts = [
        'quantita' => 'integer',
    ];

    public function utenteCompratore()
    {
        return $this->belongsTo(UtenteCompratore::class, 'id_utente_compratore');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'id_prodotto');
    }

    // Scope per filtrare per tipo
    public function scopeDesideri($query)
    {
        return $query->where('tipo', 'desideri');
    }

    public function scopeCarrello($query)
    {
        return $query->where('tipo', 'carrello');
    }
}
