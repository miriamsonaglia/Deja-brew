<?php

namespace App\Models;

class Categoria extends BaseModel
{
    protected $table = 'categorie';
    protected $fillable = ['descrizione'];

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class);
    }
}
