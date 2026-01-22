<?php

require_once __DIR__ . '/../bootstrap.php';

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    
    protected $primaryKey = 'id';
    
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
    
    public $timestamps = false;
    
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }
}