<?php

namespace App\Models;

class Lista extends Model
{
    use HasFactory;

    protected $table = 'liste';
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
