<?php

namespace App\Models;

class Aroma extends BaseModel
{
    protected $table = 'aroma';
    protected $fillable = ['gusto'];

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class);
    }
}
