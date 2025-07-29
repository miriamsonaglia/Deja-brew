<?php

namespace App\Models;
require_once "./Models/BaseModel.php";
use App\Models\BaseModel;

class Categoria extends BaseModel
{
    protected $table = 'categoria';
    protected $fillable = ['descrizione'];

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class);
    }
}
