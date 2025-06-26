<?php
    namespace App\Models;

    class UtenteVenditore extends BaseModel
    {
        protected $table = 'utenteVenditore';
        protected $fillable = ['id_utente', 'descrizione'];

        public function user()
        {
            return $this->belongsTo(Utente::class);
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
?>