<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Gestión de Centros
    Route::post('/centers', [AdminController::class, 'createCenter'])->name('centers.create');
    
    // Gestión de Participantes
    Route::post('/participants', [AdminController::class, 'addParticipant'])->name('participants.add');
    
    // PDF
    Route::get('/pdf/pair-cover/{userId}', [AdminController::class, 'generatePairPdf'])->name('pdf.pair');
});
