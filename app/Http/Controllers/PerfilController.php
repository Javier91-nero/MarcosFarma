<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Cliente;

class PerfilController extends Controller
{
    public function mostrarPerfil()
    {
        $token = Session::get('token');

        if (!$token) {
            // No hay sesión activa, redirigir a login
            return redirect()->route('login.form')->with('error', 'Por favor inicie sesión para acceder al perfil.');
        }

        $cliente = Cliente::where('token', $token)->first();

        if (!$cliente) {
            // Token inválido o usuario no encontrado
            Session::flush();
            return redirect()->route('login.form')->with('error', 'Sesión inválida, por favor inicie sesión nuevamente.');
        }

        return view('perfil', compact('cliente'));
    }

    public function actualizarPerfil(Request $request)
    {
        $token = Session::get('token');

        if (!$token) {
            return redirect()->route('login.form')->with('error', 'Por favor inicie sesión para actualizar el perfil.');
        }

        $cliente = Cliente::where('token', $token)->first();

        if (!$cliente) {
            Session::flush();
            return redirect()->route('login.form')->with('error', 'Sesión inválida, por favor inicie sesión nuevamente.');
        }

        // Validar datos
        $request->validate([
            'nombre'   => 'required|string|max:20',
            'apellido' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:9',
        ]);

        // Actualizar datos
        $cliente->nombre   = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->telefono = $request->telefono;
        $cliente->save();

        return redirect()->route('perfil.mostrar')->with('success', 'Perfil actualizado correctamente.');
    }
}
