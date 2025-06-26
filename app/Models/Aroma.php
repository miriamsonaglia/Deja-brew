<?php

namespace App\Models;

class Aroma extends Model
{
    use HasFactory;

    protected $table = 'aromi';
    protected $fillable = ['gusto'];

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class);
    }
}
