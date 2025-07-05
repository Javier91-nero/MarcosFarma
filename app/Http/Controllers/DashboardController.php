<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
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

    public function mostrarDashboard()
    {
        if (!session()->has('token') || session('rol') !== 'admin') {
            return redirect()->route('login.form')->with([
                'alert_message' => 'Debes iniciar sesión como administrador.',
                'alert_type' => 'warning',
            ]);
        }

        $totalClientes = DB::table('cliente')->count();
        $totalProductos = DB::table('productos')->count();

        $productosPorVencer = DB::table('lotes')
            ->whereBetween('fecha_vencimiento', [now(), now()->addDays(30)])
            ->count();

        $productoMasVendido = DB::table('detalle_pedido as dp')
            ->join('productos as p', 'dp.id_producto', '=', 'p.id_producto')
            ->select('p.nombre', DB::raw('SUM(dp.cantidad) as total'))
            ->groupBy('p.id_producto', 'p.nombre')
            ->orderByDesc('total')
            ->first();

        $masVendidos = DB::table('detalle_pedido as dp')
            ->join('productos as p', 'dp.id_producto', '=', 'p.id_producto')
            ->select('p.nombre', DB::raw('SUM(dp.cantidad) as total'))
            ->groupBy('p.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $clientesPorDia = DB::table('cliente')
            ->select(DB::raw("DATE(created_at) as fecha"), DB::raw('COUNT(*) as total'))
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy('fecha', 'asc')
            ->get()
            ->pluck('total', 'fecha')
            ->toArray();

        $ultimosPedidos = DB::table('pedidos')
            ->join('cliente', 'pedidos.id_cliente', '=', 'cliente.id_cliente')
            ->select('pedidos.id_pedido', 'cliente.nombre', 'pedidos.total', 'pedidos.fecha')
            ->orderByDesc('pedidos.fecha')
            ->limit(5)
            ->get();

        $productosCriticos = DB::table('productos as p')
            ->join('lotes as l', 'p.id_producto', '=', 'l.id_producto')
            ->where(function ($query) {
                $query->where('l.fecha_vencimiento', '<', now())
                      ->orWhere('l.cantidad', '<=', 0);
            })
            ->select('p.nombre', 'l.nro_lote', 'l.fecha_vencimiento', 'l.cantidad')
            ->get();

        return view('dashboard.index', [
            'nombre'              => session('nombre'),
            'token'               => session('token'),
            'totalClientes'       => $totalClientes,
            'totalProductos'      => $totalProductos,
            'productosPorVencer'  => $productosPorVencer,
            'productoMasVendido'  => $productoMasVendido,
            'masVendidos'         => $masVendidos,
            'clientesPorMes'      => $clientesPorDia, // mejor renombrado sería clientesPorDia
            'ultimosPedidos'      => $ultimosPedidos,
            'productosCriticos'   => $productosCriticos
        ]);
    }

    public function usuarios()
    {
        if (!session()->has('token') || session('rol') !== 'admin') {
            return redirect()->route('login.form')->with([
                'alert_message' => 'Debes iniciar sesión como administrador.',
                'alert_type' => 'warning',
            ]);
        }

        $usuarios = DB::table('cliente')
            ->join('cuenta', 'cliente.id_cliente', '=', 'cuenta.id_cliente')
            ->where('cliente.rol', 'cliente')
            ->select(
                'cliente.id_cliente',
                'cliente.nombre',
                'cliente.apellido',
                'cliente.dni',
                'cliente.telefono',
                'cliente.correo',
                'cuenta.validacion'
            )
            ->get();

        return view('dashboard.users', [
            'usuarios' => $usuarios,
            'nombre'   => session('nombre'),
            'token'    => session('token'),
        ]);
    }

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