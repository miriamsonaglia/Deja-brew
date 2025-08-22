<?php

namespace App\Models;
require_once "./Models/BaseModel.php";

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
