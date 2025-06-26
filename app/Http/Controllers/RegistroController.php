<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Mail\CodigoVerificacionMail;

class RegistroController extends Controller
{
    public function mostrarFormulario()
    {
        return view('registro');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:cliente,dni',
            'telefono' => 'nullable|string|max:9',
            'correo' => 'required|email|unique:cliente,correo',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $token = bin2hex(random_bytes(5));
        $codigo_verificacion = strtoupper(str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT));

        DB::beginTransaction();

        try {
            $id_cliente = DB::table('cliente')->insertGetId([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'dni' => $request->dni,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'rol' => 'cliente',
                'token' => $token,
            ]);

            DB::table('cuenta')->insert([
                'id_cliente' => $id_cliente,
                'contrasena' => Hash::make($request->password),
                'validacion' => 0,
                'codigo_verificacion' => $codigo_verificacion,
                'codigo_enviado_en' => now(),
            ]);

            DB::commit();

            Mail::to($request->correo)->send(new CodigoVerificacionMail($codigo_verificacion));

            return redirect()->route('registro.verificar')->with([
                'correo_temp' => $request->correo,
                'mensaje' => 'Código de verificación enviado al correo.'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al registrar usuario. Intente nuevamente.']);
        }
    }

    public function mostrarFormularioVerificacion(Request $request)
    {
        $correo_temp = session('correo_temp', '');
        if (!$correo_temp) {
            return redirect()->route('registro.formulario');
        }
        return view('verificador', ['correoTemp' => $correo_temp]);
    }

    public function confirmarCodigo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|size:8',
            'correo_temp' => 'required|email',
        ]);

        $cuenta = DB::table('cuenta')
            ->join('cliente', 'cuenta.id_cliente', '=', 'cliente.id_cliente')
            ->where('cliente.correo', $request->correo_temp)
            ->where('cuenta.validacion', 0)
            ->first();

        if ($cuenta && strtoupper($cuenta->codigo_verificacion) === strtoupper($request->codigo)) {
            DB::table('cuenta')
                ->where('id_cliente', $cuenta->id_cliente)
                ->update([
                    'validacion' => 1,
                    'codigo_verificacion' => null,
                    'codigo_enviado_en' => null,
                ]);

            return redirect()->route('catalog')->with('mensaje', 'Código confirmado correctamente.');
        }

        return back()->withErrors(['codigo' => 'Código incorrecto. Intente nuevamente.'])->withInput();
    }

    public function reenviarCodigo(Request $request)
    {
        $request->validate([
            'correo_temp' => 'required|email',
        ]);

        $cuenta = DB::table('cuenta')
            ->join('cliente', 'cuenta.id_cliente', '=', 'cliente.id_cliente')
            ->where('cliente.correo', $request->correo_temp)
            ->where('cuenta.validacion', 0)
            ->select('cuenta.*', 'cliente.correo')
            ->first();

        if (!$cuenta) {
            return back()->withErrors(['correo_temp' => 'No se pudo reenviar el código. Verifique el correo.']);
        }

        // Verificar el tiempo desde el último envío
        if ($cuenta->codigo_enviado_en && Carbon::parse($cuenta->codigo_enviado_en)->diffInSeconds(now()) < 20) {
            $segundos_restantes = 20 - Carbon::parse($cuenta->codigo_enviado_en)->diffInSeconds(now());
            return back()->withErrors(['correo_temp' => "Debes esperar $segundos_restantes segundos antes de reenviar."]);
        }

        $nuevo_codigo = strtoupper(str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT));

        DB::table('cuenta')
            ->where('id_cliente', $cuenta->id_cliente)
            ->update([
                'codigo_verificacion' => $nuevo_codigo,
                'codigo_enviado_en' => now(),
            ]);

        Mail::to($request->correo_temp)->send(new CodigoVerificacionMail($nuevo_codigo));

        return back()->with([
            'mensaje' => 'Nuevo código enviado al correo.',
            'correo_temp' => $request->correo_temp,
        ]);
    }
}