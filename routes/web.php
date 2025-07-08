<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController; !!NO!! Creiamo noi un controller per ogni pagina avente una logica specifica <3
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form'); // VEDI: app/Http/Controllers/LoginController
Route::post('/login', [LoginController::class, 'login'])->name('login');

// routes/web.php
Route::get('/', [HomeController::class, 'index'])->name('home');