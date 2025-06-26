<?php

namespace App\Models;

class Recensione extends Model
{
    use HasFactory;

    protected $table = 'recensioni';
    protected $fillable = [
        'id_utente',
        'id_prodotto',
        'stelle'
    ];

    protected $casts = [
        'stelle' => 'integer',
    ];

    public function utente()
    {
        return $this->belongsTo(User::class, 'id_utente');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'id_prodotto');
    }
}
