<?php

namespace App\Models;
require_once __DIR__ . '/BaseModel.php';
require_once __DIR__ . '/Prodotto.php';

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
