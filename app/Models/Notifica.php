<?php

namespace App\Models;

class Notifica extends BaseModel
{
    protected $table = 'notifiche';
    protected $fillable = [
        'tipo',
        'impostazione'
    ];

    protected $casts = [
        'impostazione' => 'boolean',
    ];
}
