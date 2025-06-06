<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArduinoController;
use App\Http\Controllers\TriggerController;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLogin'])->name('inicioSesion');

Route::get('login', [AuthController::class, 'showLogin'])->name('inicioSesion');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('cerrarSesion');

Route::get('registro', [AuthController::class, 'showRegister'])->name('registro');
Route::post('registro', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('microcontrolador', [ArduinoController::class, 'index'])->name('microcontrolador');
    Route::get('arduino/registro', [ArduinoController::class, 'create'])->name('registroArduino');
    Route::post('arduino/registro', [ArduinoController::class, 'store']);
    Route::post('arduino/{id}/eliminar', [ArduinoController::class, 'destroy'])->name('eliminar_arduino');
    Route::post('arduino/{id}/editar', [ArduinoController::class, 'update'])->name('editar_arduino');

    Route::get('arduino/{arduino}/triggers', [TriggerController::class, 'index'])->name('triggers');
    Route::get('arduino/{arduino}/triggers/registro', [TriggerController::class, 'create'])->name('registroTriggers');
    Route::post('arduino/{arduino}/triggers/registro', [TriggerController::class, 'store']);
    Route::post('arduino/{arduino}/triggers/{trigger}/editar', [TriggerController::class, 'update'])->name('editar_trigger');
    Route::post('arduino/{arduino}/triggers/{trigger}/eliminar', [TriggerController::class, 'destroy'])->name('eliminar_trigger');
});

Route::get('recuperar', function() { return view('auth.forgot_password'); })->name('recuperar');
Route::post('recuperar', [AuthController::class, 'enviarOtp'])->name('enviar_otp');
Route::get('verificar-otp', [AuthController::class, 'vistaVerificarOtp'])->name('vista_verificar_otp');
Route::post('verificar-otp', [AuthController::class, 'verificarOtp'])->name('verificar_otp');
Route::get('reset-password', [AuthController::class, 'vistaResetPassword'])->name('vista_reset_password');
Route::post('reset-password', [AuthController::class, 'actualizarPassword'])->name('actualizar_password');
