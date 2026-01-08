<?php

require_once __DIR__ . '/bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;

// Pulizia preventiva (opzionale, commenta se non vuoi cancellare i dati esistenti)
// DB::statement('PRAGMA foreign_keys = OFF;');
// foreach (['fattura', 'recensione', 'lista', 'ordine', 'prodotto', 'utenteCompratore', 'utenteVenditore', 'utente'] as $table) {
//     DB::table($table)->truncate();
// }
// DB::statement('PRAGMA foreign_keys = ON;');

echo "Inizio seeding del database Deja-brew...\n\n";

// 1. Tipi di notifica
DB::table('tipo_notifica')->insert([
    ['descrizione' => 'Nuovo ordine ricevuto'],
    ['descrizione' => 'Recensione ricevuta'],
    ['descrizione' => 'Prodotto aggiunto al carrello'],
    ['descrizione' => 'Offerta speciale'],
    ['descrizione' => 'Ordine spedito'],
]);

echo "✓ tipo_notifica popolata\n";

// 2. Categorie
$categorie = [
    'Caffè in grani',
    'Caffè macinato',
    'Capsule compatibili Nespresso',
    'Capsule compatibili Dolce Gusto',
    'Caffè solubile',
    'Accessori'
];

foreach ($categorie as $cat) {
    DB::table('categoria')->insert(['descrizione' => $cat]);
}

echo "✓ categoria popolata\n";

// 3. Aromi
$aromi = ['Cioccolato', 'Caramello', 'Nocciola', 'Vaniglia', 'Neutro', 'Intenso', 'Fruttato'];

foreach ($aromi as $aroma) {
    DB::table('aroma')->insert(['gusto' => $aroma]);
}

echo "✓ aroma popolata\n";

// 4. Utenti (compratori e venditori)
$utenti = [
    // Compratori
    ['nome' => 'Mario', 'cognome' => 'Rossi', 'email' => 'mario.rossi@email.it', 'username' => 'mariorossi', 'password' => password_hash('password123', PASSWORD_DEFAULT), 'immagine_profilo' => null],
    ['nome' => 'Laura', 'cognome' => 'Bianchi', 'email' => 'laura.bianchi@email.it', 'username' => 'laurab', 'password' => password_hash('password123', PASSWORD_DEFAULT), 'immagine_profilo' => null],
    ['nome' => 'Giulia', 'cognome' => 'Verdi', 'email' => 'giulia.verdi@email.it', 'username' => 'giuliav', 'password' => password_hash('password123', PASSWORD_DEFAULT), 'immagine_profilo' => null],

    // Venditori
    ['nome' => 'Caffè', 'cognome' => 'Verona', 'email' => 'info@caffe verona.it', 'username' => 'caffe_verona', 'password' => password_hash('venditore123', PASSWORD_DEFAULT), 'immagine_profilo' => null],
    ['nome' => 'Torrefazione', 'cognome' => 'Artigianale', 'email' => 'vendite@torrefazione.it', 'username' => 'torrefazione', 'password' => password_hash('venditore123', PASSWORD_DEFAULT), 'immagine_profilo' => null],
];

foreach ($utenti as $utente) {
    DB::table('utente')->insert($utente);
}

echo "✓ utente popolata (3 compratori + 2 venditori)\n";

// 5. UtenteCompratore e UtenteVenditore
$compratoriIds = DB::table('utente')->whereIn('username', ['mariorossi', 'laurab', 'giuliav'])->pluck('id');
$venditoriIds = DB::table('utente')->whereIn('username', ['caffe_verona', 'torrefazione'])->pluck('id');

foreach ($compratoriIds as $id) {
    DB::table('utenteCompratore')->insert(['id_utente' => $id]);
}

foreach ($venditoriIds as $id) {
    DB::table('utenteVenditore')->insert([
        'id_utente' => $id,
        'descrizione' => 'Torrefazione artigianale con passione dal 1990',
        'paese' => 'Italia',
        'cellulare' => '+39 345 1234567',
    ]);
}

echo "✓ utenteCompratore e utenteVenditore popolate\n";

// 6. ImpostazioniUtente (una per ogni utente)
$allUserIds = DB::table('utente')->pluck('id');
foreach ($allUserIds as $id) {
    DB::table('impostazioniUtente')->insert([
        'id_utente' => $id,
        'tema' => 'light',
        'notifiche' => true,
    ]);
}

echo "✓ impostazioniUtente popolata\n";

// 7. Prodotti
$venditore1 = DB::table('utenteVenditore')->where('id_utente', $venditoriIds[0])->value('id');
$venditore2 = DB::table('utenteVenditore')->where('id_utente', $venditoriIds[1])->value('id');

$prodotti = [
    ['nome' => 'Espresso Classico', 'tipo' => 'grani', 'prezzo' => 12.90, 'intensita' => 'Media', 'fotografia' => null, 'provenienza' => 'Brasile', 'peso' => 1.000, 'id_venditore' => $venditore1, 'categoria_id' => 1, 'aroma_id' => 5],
    ['nome' => 'Arabica 100%', 'tipo' => 'macinato', 'prezzo' => 15.50, 'intensita' => 'Delicata', 'fotografia' => null, 'provenienza' => 'Etiopia', 'peso' => 0.250, 'id_venditore' => $venditore1, 'categoria_id' => 2, 'aroma_id' => 7],
    ['nome' => 'Nocciola Cremosa', 'tipo' => 'capsule', 'prezzo' => 4.99, 'intensita' => 'Media', 'fotografia' => null, 'provenienza' => 'Italia', 'peso' => null, 'id_venditore' => $venditore2, 'categoria_id' => 3, 'aroma_id' => 3],
    ['nome' => 'Intenso Decaffeinato', 'tipo' => 'macinato', 'prezzo' => 14.20, 'intensita' => 'Forte', 'fotografia' => null, 'provenienza' => 'Colombia', 'peso' => 0.500, 'id_venditore' => $venditore2, 'categoria_id' => 2, 'aroma_id' => 6],
    ['nome' => 'Kit Macchina Moka', 'tipo' => 'accessorio', 'prezzo' => 29.90, 'intensita' => null, 'fotografia' => null, 'provenienza' => 'Italia', 'peso' => null, 'id_venditore' => $venditore1, 'categoria_id' => 6, 'aroma_id' => null],
];

foreach ($prodotti as $prod) {
    DB::table('prodotto')->insert($prod);
}

echo "✓ prodotto popolata (5 prodotti)\n";

// 8. Liste (carrello e desideri)
$compratoreId = DB::table('utenteCompratore')->where('id_utente', $compratoriIds[0])->value('id'); // Mario Rossi
$prodottoIds = DB::table('prodotto')->pluck('id');

DB::table('lista')->insert([
    ['id_utente_compratore' => $compratoreId, 'id_prodotto' => $prodottoIds[0], 'tipo' => 'carrello', 'quantita' => 2],
    ['id_utente_compratore' => $compratoreId, 'id_prodotto' => $prodottoIds[2], 'tipo' => 'carrello', 'quantita' => 1],
    ['id_utente_compratore' => $compratoreId, 'id_prodotto' => $prodottoIds[1], 'tipo' => 'desideri', 'quantita' => 1],
    ['id_utente_compratore' => $compratoreId, 'id_prodotto' => $prodottoIds[3], 'tipo' => 'desideri', 'quantita' => 1],
]);

echo "✓ lista (carrello e desideri) popolata\n";

// 9. Ordini
DB::table('ordine')->insert([
    ['id_utente' => $compratoriIds[0], 'id_prodotto' => $prodottoIds[0], 'status' => 'completato', 'prezzo_totale' => 25.80, 'quantita' => 2],
    ['id_utente' => $compratoriIds[1], 'id_prodotto' => $prodottoIds[2], 'status' => 'in elaborazione', 'prezzo_totale' => 9.98, 'quantita' => 2],
    ['id_utente' => $compratoriIds[2], 'id_prodotto' => $prodottoIds[4], 'status' => 'completato', 'prezzo_totale' => 29.90, 'quantita' => 1],
]);

$ordineIds = DB::table('ordine')->pluck('id');

echo "✓ ordine popolata\n";

// 10. Fatture
DB::table('fattura')->insert([
    ['id_utente' => $compratoriIds[0], 'id_venditore' => $venditore1, 'id_ordine' => $ordineIds[0], 'transaction_id' => 'txn_123456789'],
    ['id_utente' => $compratoriIds[2], 'id_venditore' => $venditore1, 'id_ordine' => $ordineIds[2], 'transaction_id' => 'txn_987654321'],
]);

echo "✓ fattura popolata\n";

// 11. Recensioni
DB::table('recensione')->insert([
    ['id_utente' => $compratoriIds[0], 'id_prodotto' => $prodottoIds[0], 'stelle' => 5, 'testo' => 'Ottimo caffè, aroma intenso e persistente!'],
    ['id_utente' => $compratoriIds[1], 'id_prodotto' => $prodottoIds[2], 'stelle' => 4, 'testo' => 'Buona crema, gusto nocciola ben bilanciato.'],
    ['id_utente' => $compratoriIds[2], 'id_prodotto' => $prodottoIds[4], 'stelle' => 5, 'testo' => 'Moka di ottima qualità, consegna veloce.'],
]);

echo "✓ recensione popolata\n";

// 12. Carte di credito (solo per i compratori)
DB::table('cartaDiCredito')->insert([
    ['id_utente' => $compratoriIds[0], 'circuito_pagamento' => 'Visa', 'codice_carta' => 'encrypted_4111111111111111', 'cvv_carta' => 'enc_123', 'nome_titolare' => 'Mario Rossi', 'scadenza_mese' => 12, 'scadenza_anno' => 2028],
    ['id_utente' => $compratoriIds[1], 'circuito_pagamento' => 'MasterCard', 'codice_carta' => 'encrypted_5555555555554444', 'cvv_carta' => 'enc_456', 'nome_titolare' => 'Laura Bianchi', 'scadenza_mese' => 6, 'scadenza_anno' => 2027],
]);

echo "✓ cartaDiCredito popolata\n";

// 13. Notifiche (ora collegate correttamente a tipo_notifica)
$tipoOrdine = DB::table('tipo_notifica')->where('descrizione', 'Nuovo ordine ricevuto')->value('id');
$tipoRecensione = DB::table('tipo_notifica')->where('descrizione', 'Recensione ricevuta')->value('id');

DB::table('notifica')->insert([
    ['id_tipo_notifica' => $tipoOrdine, 'impostazione' => true],
    ['id_tipo_notifica' => $tipoOrdine, 'impostazione' => false], // esempio di disattivata
    ['id_tipo_notifica' => $tipoRecensione, 'impostazione' => true],
    ['id_tipo_notifica' => $tipoRecensione, 'impostazione' => true],
]);

echo "✓ notifica popolata (con foreign key corretta)\n";

echo "\n====================================\n";
echo "Seeding completato con successo! ☕\n";
echo "Ora hai dati realistici per testare il tuo progetto Deja-brew.\n";
echo "====================================\n";