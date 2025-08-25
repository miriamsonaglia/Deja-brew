<?php

namespace App\Models;
require_once "./Models/BaseModel.php";

use App\Models\Utente;
use App\Models\Prodotto;


class Recensione extends BaseModel
{
    protected $table = 'recensione';
    protected $fillable = [
        'id_utente',
        'id_prodotto',
        'stelle',
        'testo'
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

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($recensione) {
            if ($recensione->stelle < 1 || $recensione->stelle > 5) {
                throw new \InvalidArgumentException('Le stelle devono essere comprese tra 1 e 5');
            }
        });
    }
}