<?php

namespace App\Models;
require_once __DIR__ . '/BaseModel.php';

use App\Models\TipoNotifica;

class Notifica extends BaseModel
{
    protected $table = 'notifica';

    protected $fillable = [
        'id_tipo_notifica',
        'impostazione'
    ];

    protected $casts = [
        'impostazione' => 'boolean',
    ];

    // Relazione con TipoNotifica
    public function tipo()
    {
        return $this->belongsTo(TipoNotifica::class, 'id_tipo_notifica');
    }
}