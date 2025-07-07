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
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Log;
use Sentry\State\HubInterface;

// Página principal (Inicio)
Route::get('/', function () {
    return view('index');
})->name('home');

// Catálogo público de productos
Route::get('/catalog', [ProductoController::class, 'index'])->name('catalog');

// Registro de cliente
Route::get('/register', [RegistroController::class, 'mostrarFormulario'])->name('registro.formulario');
Route::post('/register', [RegistroController::class, 'registrar'])->name('registro.registrar');

// Verificación de cuenta
Route::get('/register/verificar', [RegistroController::class, 'mostrarFormularioVerificacion'])->name('registro.verificar');
Route::post('/register/confirmar', [RegistroController::class, 'confirmarCodigo'])->name('registro.confirmar');

// Reenviar código de verificación
Route::post('/register/reenviar', [RegistroController::class, 'reenviarCodigo'])->name('registro.reenviar');

// Login y logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Validación del token de administrador
Route::get('/dashboard/validation', [DashboardController::class, 'validarToken'])->name('dashboard.validation');

// Dashboard principal (Administrador)
Route::get('/dashboard', [DashboardController::class, 'mostrarDashboard'])->name('dashboard.index');

// Exportar pedidos a CSV
Route::get('/dashboard/exportar-csv', [DashboardController::class, 'exportarCSV'])->name('dashboard.export.csv');

// NUEVO: Vista de usuarios registrados (para administración)
Route::get('/dashboard/usuarios', [DashboardController::class, 'usuarios'])->name('dashboard.usuarios');

// NUEVO: Vista del perfil del administrador
Route::get('/dashboard/perfil', function () {
    return view('dashboard.perfil');
})->name('dashboard.perfil');

// CRUD de productos (Administración)
Route::get('/products', [DProductController::class, 'index'])->name('product.index');
Route::post('/products', [DProductController::class, 'store'])->name('product.store');
Route::get('/product/{id}', [DProductController::class, 'show'])->name('product.show');
Route::put('/product/{id}/update', [DProductController::class, 'update'])->name('product.update');
Route::get('/product/{id}/details', [DProductController::class, 'details'])->name('product.details');

// Perfil de cliente (editar datos)
Route::get('/perfil', [PerfilController::class, 'mostrarPerfil'])->name('perfil.mostrar');
Route::post('/perfil', [PerfilController::class, 'actualizarPerfil'])->name('perfil.actualizar');

// Recuperar contraseña
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Historial de pedidos del cliente
Route::get('/mis-pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
Route::get('/mis-pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
Route::post('/mis-pedidos/{id}/repetir', [PedidoController::class, 'repetir'])->name('pedidos.repetir');

// Carrito de compras
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::get('/carrito', [CarritoController::class, 'mostrar'])->name('carrito.index');
Route::post('/carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::post('/carrito/eliminar', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/procesar', [CheckoutController::class, 'procesar'])->name('checkout.procesar');
Route::post('/checkout', [CheckoutController::class, 'realizar'])->name('checkout.realizar');

// Factura
Route::get('/checkout/factura/{id}', [CheckoutController::class, 'factura'])->name('checkout.factura');

// Ruta para probar Sentry
Route::get('/sentry-test', function (HubInterface $sentry) {
    try {
        throw new Exception('⚠️ Excepción de prueba enviada a Sentry desde Laravel.');
    } catch (\Throwable $e) {
        $sentry->captureException($e);
        Log::error('Excepción capturada y enviada a Sentry: ' . $e->getMessage());
    }

    return '✅ Error de prueba enviado correctamente a Sentry.';
});