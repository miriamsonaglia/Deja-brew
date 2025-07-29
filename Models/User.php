<?php

require_once __DIR__ . '/../bootstrap.php';

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Nome della tabella (opzionale se segue la convenzione)
    protected $table = 'users';
    
    // Chiave primaria (default: 'id')
    protected $primaryKey = 'id';
    
    // Campi che possono essere assegnati in massa
    protected $fillable = [
        'name',
        'email',
        'password'
    ];
    
    // Campi nascosti (per serializzazione JSON)
    protected $hidden = [
        'password',
        'remember_token'
    ];
    
    // Cast automatici
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Disabilita timestamps se non usi created_at/updated_at
    public $timestamps = false;
    
    // Relazioni esempio
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    
    // Accessor esempio
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    // Mutator esempio
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }
}