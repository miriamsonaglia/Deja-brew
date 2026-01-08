<?php
// File per calcolare il numero di notifiche attive per l'utente corrente
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/role.php';

use App\Models\Ordine;
use App\Models\UtenteVenditore;
use App\Models\Prodotto;

function getNotificationCount() {
    if (!isset($_SESSION['UserRole']) || !isset($_SESSION['LoggedUser']['id'])) {
        return 0;
    }

    $userRole = $_SESSION['UserRole'];
    $userId = $_SESSION['LoggedUser']['id'];
    $count = 0;

    if ($userRole === Role::BUYER->value) {
        // Per il compratore: conta gli ordini spediti che deve confermare
        $count = Ordine::where('id_utente', $userId)
            ->where('status', 'spedito')
            ->count();
    } elseif ($userRole === Role::VENDOR->value) {
        // Per il venditore: conta gli ordini confermati che deve spedire
        $venditore = UtenteVenditore::where('id_utente', $userId)->first();
        if ($venditore) {
            $prodottiIds = Prodotto::where('id_venditore', $venditore->id)->pluck('id');
            $count = Ordine::whereIn('id_prodotto', $prodottiIds)
                ->where('status', 'confermato')
                ->count();
        }
    }

    return $count;
}
