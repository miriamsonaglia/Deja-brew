<?php

namespace App\Models;
require_once __DIR__ . '/BaseModel.php';

class TipoNotifica extends BaseModel
{
    protected $table = 'tipo_notifica'; 

    protected $fillable = [
        'descrizione'   
    ];

    
    public function notifiche()
    {
        return $this->hasMany(Notifica::class, 'tipo'); 
    }
}