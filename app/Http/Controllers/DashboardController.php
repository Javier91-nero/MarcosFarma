<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    // Verifica el token tras el login
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
            session([
                'id_cliente' => $admin->id_cliente,
                'nombre'     => $admin->nombre,
                'rol'        => $admin->rol,
                'token'      => $admin->token,
            ]);

            return redirect()->route('dashboard.index');
        }

        return redirect()->route('login.form')->with([
            'alert_message' => 'Acceso denegado. Token inválido o sin permisos de administrador.',
            'alert_type' => 'danger',
        ]);
    }

    // Muestra el dashboard solo si es admin
    public function mostrarDashboard()
    {
        if (!session()->has('token') || session('rol') !== 'admin') {
            return redirect()->route('login.form')->with([
                'alert_message' => 'Debes iniciar sesión como administrador.',
                'alert_type' => 'warning',
            ]);
        }

        // Gráfico de pedidos por mes
        $pedidosPorMes = DB::table('pedidos')
            ->select(DB::raw('MONTH(fecha) as mes'), DB::raw('COUNT(*) as cantidad'))
            ->groupBy(DB::raw('MONTH(fecha)'))
            ->pluck('cantidad', 'mes')
            ->toArray(); // 👈 NECESARIO

        // Últimos 5 pedidos
        $ultimosPedidos = DB::table('pedidos')
            ->join('cliente', 'pedidos.id_cliente', '=', 'cliente.id_cliente')
            ->select('pedidos.id_pedido', 'cliente.nombre', 'pedidos.total', 'pedidos.fecha')
            ->orderByDesc('pedidos.fecha')
            ->limit(5)
            ->get();

        // Productos críticos (vencidos o en 0)
        $productosCriticos = DB::table('productos as p')
            ->join('lotes as l', 'p.id_producto', '=', 'l.id_producto')
            ->where(function ($query) {
                $query->where('l.fecha_vencimiento', '<', now())
                      ->orWhere('l.cantidad', '<=', 0);
            })
            ->select('p.nombre', 'l.nro_lote', 'l.fecha_vencimiento', 'l.cantidad')
            ->get();

        return view('dashboard.index', [
            'nombre'            => session('nombre'),
            'token'             => session('token'),
            'pedidosPorMes'     => $pedidosPorMes, // 👈 Ya como array
            'ultimosPedidos'    => $ultimosPedidos,
            'productosCriticos' => $productosCriticos
        ]);
    }

    // Exportar pedidos a CSV
    public function exportarCSV()
    {
        $pedidos = DB::table('pedidos')
            ->join('cliente', 'pedidos.id_cliente', '=', 'cliente.id_cliente')
            ->select('pedidos.id_pedido', 'cliente.nombre', 'pedidos.total', 'pedidos.fecha')
            ->orderByDesc('pedidos.fecha')
            ->get();

        $response = new StreamedResponse(function () use ($pedidos) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Cliente', 'Total', 'Fecha']);

            foreach ($pedidos as $pedido) {
                fputcsv($handle, [
                    $pedido->id_pedido,
                    $pedido->nombre,
                    $pedido->total,
                    $pedido->fecha,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="pedidos.csv"');

        return $response;
    }
}