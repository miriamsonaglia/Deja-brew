<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Prodotto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Carica le categorie con i prodotti associati
        $categories = Categoria::with(['prodotti' => function($query) {
            $query->with(['venditore', 'aroma', 'recensioni'])
                  ->orderBy('created_at', 'desc')
                  ->limit(8);
        }])
        ->orderBy('descrizione')
        ->get();

        // Conta elementi nel carrello (se hai sessioni/auth)
        $cartCount = session()->get('cart') ? count(session()->get('cart')) : 0;

        return view('home', compact('categories', 'cartCount'));
    }
}