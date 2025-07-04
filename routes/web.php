<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\DProductController;
use App\Http\Controllers\PerfilController; 
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Página principal
Route::get('/', function () {
    return view('index');
})->name('home');

// Catálogo público
Route::get('/catalog', [ProductoController::class, 'index'])->name('catalog');

// Registro
Route::get('/register', [RegistroController::class, 'mostrarFormulario'])->name('registro.formulario');
Route::post('/register', [RegistroController::class, 'registrar'])->name('registro.registrar');

// Verificación de cuenta
Route::get('/register/verificar', [RegistroController::class, 'mostrarFormularioVerificacion'])->name('registro.verificar');
Route::post('/register/confirmar', [RegistroController::class, 'confirmarCodigo'])->name('registro.confirmar');

// Reenviar código de verificación
Route::post('/register/reenviar', [RegistroController::class, 'reenviarCodigo'])->name('registro.reenviar');

// Login / Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Validación del token de administrador
Route::get('/dashboard/validation', [DashboardController::class, 'validarToken'])->name('dashboard.validation');

// Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'mostrarDashboard'])->name('dashboard.index');

// CRUD de productos para admin
Route::get('/products', [DProductController::class, 'index'])->name('product.index');
Route::post('/products', [DProductController::class, 'store'])->name('product.store');
Route::get('/product/{id}', [DProductController::class, 'show'])->name('product.show');
Route::put('/product/{id}/update', [DProductController::class, 'update'])->name('product.update');

// Vista detallada de producto
Route::get('/product/{id}/details', [DProductController::class, 'details'])->name('product.details');

// Rutas para perfil de usuario con middleware de autenticación
Route::get('/perfil', [PerfilController::class, 'mostrarPerfil'])->name('perfil.mostrar');
Route::post('/perfil', [PerfilController::class, 'actualizarPerfil'])->name('perfil.actualizar');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');