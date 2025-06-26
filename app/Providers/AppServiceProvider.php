<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Cliente;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Cargar cliente autenticado en todas las vistas
        View::composer('*', function ($view) {
            $clienteAuth = null;

            if (Session::has('id_cliente') && Session::has('token')) {
                $cliente = Cliente::where('id_cliente', Session::get('id_cliente'))
                                  ->where('token', Session::get('token'))
                                  ->first();

                if ($cliente) {
                    $clienteAuth = $cliente;
                }
            }

            $view->with('clienteAuth', $clienteAuth);
        });
    }
}
