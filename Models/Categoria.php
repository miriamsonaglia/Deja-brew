<?php

namespace App\Models;
require_once "./Models/BaseModel.php";
require_once "./Models/Prodotto.php";

use App\Models\BaseModel;
use App\Models\Prodotto;

class Categoria extends BaseModel
{
    protected $table = 'categoria';
    protected $fillable = ['descrizione'];

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class);
    }
}
