<?php

namespace App\Models;

class Recensione extends BaseModel
{
    protected $table = 'recensione';
    protected $fillable = [
        'id_utente',
        'id_prodotto',
        'stelle'
    ];

    protected $casts = [
        'stelle' => 'integer',
    ];

    public function utente()
    {
        return $this->belongsTo(Utente::class, 'id_utente');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'id_prodotto');
    }
}