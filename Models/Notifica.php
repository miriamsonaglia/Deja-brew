<?php

namespace App\Models;
require_once __DIR__ . '/BaseModel.php';

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
