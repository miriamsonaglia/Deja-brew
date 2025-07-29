<?php

namespace App\Models;

class Notifica extends BaseModel
{
    protected $table = 'notifica';
    protected $fillable = [
        'tipo',
        'impostazione'
    ];

    protected $casts = [
        'impostazione' => 'boolean',
    ];
}
