<?php

namespace App\Models;
require_once __DIR__ . '/BaseModel.php';

class TipoNotifica extends BaseModel
{
    protected $table = 'tipo_notifica'; // nome tabella suggerito

    protected $fillable = [
        'descrizione'   // es. "Nuovo ordine", "Recensione ricevuta", ecc.
    ];

    // Eventuale relazione inversa con Notifica (se vuoi collegarle)
    public function notifiche()
    {
        return $this->hasMany(Notifica::class, 'tipo'); // assumendo che 'tipo' in Notifica sia la chiave foreign
    }
}