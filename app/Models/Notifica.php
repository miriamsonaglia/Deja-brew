<?php

namespace App\Models;

class Notifica extends Model
{
    use HasFactory;

    protected $table = 'notifiche';
    protected $fillable = [
        'tipo',
        'impostazione'
    ];

    protected $casts = [
        'impostazione' => 'boolean',
    ];
}
