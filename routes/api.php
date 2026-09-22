<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Servicio web para registrar nuevos usuarios.
Route::post('/registro', [AuthController::class, 'register']);

// Servicio web para verificar las credenciales de inicio de sesión.
Route::post('/login', [AuthController::class, 'login']);