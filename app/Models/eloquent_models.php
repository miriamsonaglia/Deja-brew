<?php

// Model: User (Utente)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    // Relazioni
    public function utenteCompratore()
    {
        return $this->hasOne(UtenteCompratore::class);
    }

    public function utenteVenditore()
    {
        return $this->hasOne(UtenteVenditore::class);
    }

    public function recensioni()
    {
        return $this->hasMany(Recensione::class, 'id_utente');
    }

    public function ordini()
    {
        return $this->hasMany(Ordine::class, 'id_utente');
    }

    public function impostazioniUtente()
    {
        return $this->hasOne(ImpostazioniUtente::class, 'id_utente');
    }

    public function carteDiCredito()
    {
        return $this->hasMany(CartaDiCredito::class, 'id_utente');
    }

    public function fatture()
    {
        return $this->hasMany(Fattura::class, 'id_utente');
    }
}

// Model: UtenteCompratore
class UtenteCompratore extends Model
{
    use HasFactory;

    protected $table = 'utenti_compratori';
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function liste()
    {
        return $this->hasMany(Lista::class, 'id_utente_compratore');
    }

    public function desideri()
    {
        return $this->hasMany(Lista::class, 'id_utente_compratore')->where('tipo', 'desideri');
    }

    public function carrello()
    {
        return $this->hasMany(Lista::class, 'id_utente_compratore')->where('tipo', 'carrello');
    }
}

// Model: UtenteVenditore
class UtenteVenditore extends Model
{
    use HasFactory;

    protected $table = 'utenti_venditori';
    protected $fillable = ['user_id', 'descrizione'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class, 'id_venditore');
    }

    public function fatture()
    {
        return $this->hasMany(Fattura::class, 'id_venditore');
    }
}

// Model: Prodotto
class Prodotto extends Model
{
    use HasFactory;

    protected $table = 'prodotti';
    protected $fillable = [
        'nome',
        'tipo',
        'prezzo',
        'intensita',
        'fotografia',
        'provenienza',
        'peso',
        'id_venditore',
        'categoria_id',
        'aroma_id'
    ];

    protected $casts = [
        'prezzo' => 'decimal:2',
        'peso' => 'decimal:3',
    ];

    public function venditore()
    {
        return $this->belongsTo(UtenteVenditore::class, 'id_venditore');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function aroma()
    {
        return $this->belongsTo(Aroma::class);
    }

    public function recensioni()
    {
        return $this->hasMany(Recensione::class, 'id_prodotto');
    }

    public function ordini()
    {
        return $this->hasMany(Ordine::class, 'id_prodotto');
    }

    public function liste()
    {
        return $this->hasMany(Lista::class, 'id_prodotto');
    }
}

// Model: Aroma
class Aroma extends Model
{
    use HasFactory;

    protected $table = 'aromi';
    protected $fillable = ['gusto'];

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class);
    }
}

// Model: Recensione
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

// Model: Ordine
class Ordine extends Model
{
    use HasFactory;

    protected $table = 'ordini';
    protected $fillable = [
        'id_utente',
        'id_prodotto',
        'status',
        'prezzo_totale'
    ];

    protected $casts = [
        'prezzo_totale' => 'decimal:2',
    ];

    public function utente()
    {
        return $this->belongsTo(User::class, 'id_utente');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'id_prodotto');
    }

    public function fattura()
    {
        return $this->hasOne(Fattura::class, 'id_ordine');
    }
}

// Model: Categoria
class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorie';
    protected $fillable = ['descrizione'];

    public function prodotti()
    {
        return $this->hasMany(Prodotto::class);
    }
}

// Model: Lista (per desideri e carrello)
class Lista extends Model
{
    use HasFactory;

    protected $table = 'liste';
    protected $fillable = [
        'id_utente_compratore',
        'id_prodotto',
        'tipo', // 'desideri' o 'carrello'
        'quantita'
    ];

    protected $casts = [
        'quantita' => 'integer',
    ];

    public function utenteCompratore()
    {
        return $this->belongsTo(UtenteCompratore::class, 'id_utente_compratore');
    }

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class, 'id_prodotto');
    }

    // Scope per filtrare per tipo
    public function scopeDesideri($query)
    {
        return $query->where('tipo', 'desideri');
    }

    public function scopeCarrello($query)
    {
        return $query->where('tipo', 'carrello');
    }
}

// Model: Notifica
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

// Model: ImpostazioniUtente
class ImpostazioniUtente extends Model
{
    use HasFactory;

    protected $table = 'impostazioni_utenti';
    protected $fillable = [
        'id_utente',
        'tema',
        'notifiche'
    ];

    protected $casts = [
        'notifiche' => 'boolean',
    ];

    public function utente()
    {
        return $this->belongsTo(User::class, 'id_utente');
    }
}

// Model: CartaDiCredito
class CartaDiCredito extends Model
{
    use HasFactory;

    protected $table = 'carte_di_credito';
    protected $fillable = [
        'id_utente',
        'circuito_pagamento',
        'codice_carta',
        'cvv_carta'
    ];

    protected $hidden = [
        'codice_carta',
        'cvv_carta'
    ];

    public function utente()
    {
        return $this->belongsTo(User::class, 'id_utente');
    }
}

// Model: Fattura
class Fattura extends Model
{
    use HasFactory;

    protected $table = 'fatture';
    protected $fillable = [
        'id_utente',
        'id_venditore',
        'id_ordine'
    ];

    public function utente()
    {
        return $this->belongsTo(User::class, 'id_utente');
    }

    public function venditore()
    {
        return $this->belongsTo(UtenteVenditore::class, 'id_venditore');
    }

    public function ordine()
    {
        return $this->belongsTo(Ordine::class, 'id_ordine');
    }
}