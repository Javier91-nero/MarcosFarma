<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $cliente = DB::table('cliente')->where('correo', $request->email)->first();
        if (!$cliente) {
            return back()->withErrors(['email' => 'Correo no registrado.']);
        }

        $token = Str::random(64);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $resetLink = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

        Mail::raw("Haz clic en este enlace para restablecer tu contraseña: $resetLink", function ($message) use ($request) {
            $message->to($request->email)->subject('Restablecer contraseña - MarcosFarma');
        });

        return back()->with('status', 'Hemos enviado el enlace a tu correo.');
    }
}
