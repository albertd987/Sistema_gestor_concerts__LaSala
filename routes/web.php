<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ==================== RUTES PÚBLIQUES ====================
Route::get('/', function () {
    return redirect('/agenda'); // Redirigir home a agenda
});

// Agenda pública de concerts
Route::get('/agenda', function () {
    return view('public.agenda');
});

// Detall d'un esdeveniment específic
Route::get('/agenda/{id}', function ($id) {
    return view('public.event-detail', ['eventId' => $id]);
});

// ==================== AUTENTICACIÓ ====================
// GET routes sense middleware - el controller maneja la lógica
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// POST routes amb middleware guest (per evitar doble submit)
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== RUTES ADMIN ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/slots', function () {
        return view('admin.slots');
    });
    
    Route::get('/reserves', function () {
        return view('admin.reserves');
    });
});

// ==================== RUTES ARTISTA ====================

Route::middleware(['auth', 'role:artista'])->prefix('artista')->group(function () {
    Route::get('/dashboard', function () {
        return redirect('/artista/reservar');
    });
    
    Route::get('/reservar', function () {
        return view('artista.reservar');
    });
    
    Route::get('/reserves', function () {
        return view('artista.reserves');
    });
});