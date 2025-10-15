<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ==================== RUTES PÚBLIQUES ====================
Route::get('/', function () {
    return view('welcome');
});

// ==================== AUTENTICACIÓ ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
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
        return view('artista.dashboard');
    });
    
    // TODO: Afegir més rutes Artista
});