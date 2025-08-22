<?php

namespace App\Models;
require_once "./Models/BaseModel.php";

class Aroma extends BaseModel
{
    protected $table = 'aroma';
    protected $fillable = ['gusto'];

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class);
    }
}
