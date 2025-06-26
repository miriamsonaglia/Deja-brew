<?php

    namespace App\Models;

    class Ordine extends BaseModel
    {
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

?>
