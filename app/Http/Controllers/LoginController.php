<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\Cliente;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Buscar cliente por correo
        $cliente = Cliente::where('correo', $request->email)->first();

        // Verificar si existe y tiene cuenta relacionada
        if (!$cliente || !$cliente->cuenta) {
            return back()->with('error', 'Correo no registrado')->withInput();
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $cliente->cuenta->contrasena)) {
            return back()->with('error', 'Contraseña incorrecta')->withInput();
        }

        // Validación de cuenta pendiente
        if ($cliente->cuenta->validacion == 0) {
            session(['correo_temp' => $cliente->correo]);
            return redirect()->route('registro.verificar')
                ->with('validacion_pendiente', 'Debe ingresar el código de verificación.');
        }

        // Generar y guardar token
        $token = Str::random(10);
        $cliente->token = $token;
        $cliente->save();

        // Guardar datos en sesión
        session([
            'id_cliente' => $cliente->id_cliente,
            'nombre'     => $cliente->nombre,
            'rol'        => $cliente->rol,
            'token'      => $token,
        ]);

        // Redirigir por rol
        if ($cliente->rol === 'admin') {
            return redirect()->route('dashboard.validation', ['token' => $token]);
        } else {
            return redirect()->route('home');
        }
    }

    public function logout()
    {
        // Borrar token del cliente
        if (session()->has('id_cliente')) {
            $cliente = Cliente::find(session('id_cliente'));
            if ($cliente) {
                $cliente->token = null;
                $cliente->save();
            }
        }

        session()->flush();

        return redirect()->route('login.form');
    }
}
