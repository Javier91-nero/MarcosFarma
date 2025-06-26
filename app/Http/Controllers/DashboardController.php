<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Verifica el token tras el login y guarda los datos en sesión
    public function validarToken(Request $request)
    {
        $token = $request->query('token');

        if (empty($token)) {
            return redirect()->route('login.form');
        }

        $admin = DB::table('cliente')
            ->where('token', $token)
            ->where('rol', 'admin')
            ->first();

        if ($admin) {
            // Guardar datos en la sesión
            session([
                'id_cliente' => $admin->id_cliente,
                'nombre'     => $admin->nombre,
                'rol'        => $admin->rol,
                'token'      => $admin->token,
            ]);

            // Redirigir a dashboard sin el token en URL
            return redirect()->route('dashboard.index');
        }

        return redirect()->route('login.form')->with([
            'alert_message' => 'Acceso denegado. Token inválido o sin permisos de administrador.',
            'alert_type' => 'danger',
        ]);
    }

    // Muestra el dashboard solo si el usuario es admin y tiene sesión activa
    public function mostrarDashboard()
    {
        if (!session()->has('token') || session('rol') !== 'admin') {
            return redirect()->route('login.form')->with([
                'alert_message' => 'Debes iniciar sesión como administrador.',
                'alert_type' => 'warning',
            ]);
        }

        return view('dashboard.index', [
            'nombre' => session('nombre'),
            'token'  => session('token'),
        ]);
    }
}