<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestResultController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Login para la App
Route::post('/login', [LoginController::class, 'apiLogin']);

// Guardar resultados (Protegido si usas Sanctum, si no, déjalo libre para pruebas)
Route::post('/results', [TestResultController::class, 'store']);
