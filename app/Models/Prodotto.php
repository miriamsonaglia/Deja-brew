<?php

namespace App\Models;

class Prodotto extends BaseModel
{
    protected $table = 'prodotto';
    protected $fillable = [
        'nome',
        'tipo',
        'prezzo',
        'intensita',
        'fotografia',
        'provenienza',
        'peso',
        'id_venditore',
        'categoria_id',
        'aroma_id'
    ];

    protected $casts = [
        'prezzo' => 'decimal:2',
        'peso' => 'decimal:3',
    ];

    public function venditore()
    {
        return $this->belongsTo(UtenteVenditore::class, 'id_venditore');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function aroma()
    {
        return $this->belongsTo(Aroma::class);
    }

    public function recensioni()
    {
        return $this->hasMany(Recensione::class, 'id_prodotto');
    }

    public function ordini()
    {
        return $this->hasMany(Ordine::class, 'id_prodotto');
    }

    public function liste()
    {
        return $this->hasMany(Lista::class, 'id_prodotto');
    }
}

?>