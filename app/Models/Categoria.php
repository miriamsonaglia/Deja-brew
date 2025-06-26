<?php

namespace App\Models;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorie';
    protected $fillable = ['descrizione'];

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class);
    }
}
