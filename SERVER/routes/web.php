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
    Route::get('/centers/{id}', [AdminController::class, 'showCenter'])->name('centers.show');

    // Detalle de Pareja
    Route::get('/centers/{centerId}/pairs/{pairName}', [AdminController::class, 'showPair'])->name('pairs.show');

    // Gestión de Centros
    Route::post('/centers', [AdminController::class, 'createCenter'])->name('centers.create');
    Route::put('/centers/{id}', [AdminController::class, 'updateCenter'])->name('centers.update');
    Route::delete('/centers/{id}', [AdminController::class, 'deleteCenter'])->name('centers.delete');

    // Gestión de Parejas
    Route::post('/pairs', [AdminController::class, 'addPair'])->name('pairs.add');
    Route::put('/pairs/update', [AdminController::class, 'updatePair'])->name('pairs.update');
    Route::delete('/pairs/delete', [AdminController::class, 'deletePair'])->name('pairs.delete');

    // Gestión de Staff
    Route::post('/staff', [AdminController::class, 'addStaff'])->name('staff.add');
    Route::put('/staff/{id}', [AdminController::class, 'updateStaff'])->name('staff.update');
    Route::delete('/staff/{id}', [AdminController::class, 'deleteStaff'])->name('staff.delete');

    // Gestión de Notas
    Route::post('/notes', [AdminController::class, 'addNote'])->name('notes.add');
    Route::put('/notes/{id}', [AdminController::class, 'updateNote'])->name('notes.update');
    Route::delete('/notes/{id}', [AdminController::class, 'deleteNote'])->name('notes.delete');

    // Gestión de Admins
    Route::put('/admins/{id}', [AdminController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('/admins/{id}', [AdminController::class, 'deleteAdmin'])->name('admins.delete');

    // PDF e Impresión
    Route::get('/pdf/pair-cover/{userId}', [AdminController::class, 'generatePairPdf'])->name('pdf.pair');
    Route::get('/results/{id}/print', [AdminController::class, 'printResult'])->name('results.print');
    Route::get('/centers/{id}/print-pairs', [AdminController::class, 'printCenterPairs'])->name('centers.print_pairs');

    // Realizar Tests en Web
    Route::get('/tests/take/{userId}/{type}', [AdminController::class, 'takeTest'])->name('tests.take');
    Route::post('/tests/save', [AdminController::class, 'saveTest'])->name('tests.save');
});
