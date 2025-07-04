<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $idCliente = session('id_cliente');
        $pedidos = Pedido::where('id_cliente', $idCliente)->with('detalles.producto')->get();
        return view('pedidos.index', compact('pedidos'));
    }

    public function show($id)
    {
        $pedido = Pedido::with('detalles.producto')->findOrFail($id);
        return view('pedidos.show', compact('pedido'));
    }

    public function repetir($id)
    {
        $original = Pedido::with('detalles')->findOrFail($id);

        DB::beginTransaction();

        $nuevoPedido = Pedido::create([
            'id_cliente' => $original->id_cliente,
            'fecha' => now(),
            'total' => $original->total
        ]);

        foreach ($original->detalles as $detalle) {
            DetallePedido::create([
                'id_pedido' => $nuevoPedido->id_pedido,
                'id_producto' => $detalle->id_producto,
                'cantidad' => $detalle->cantidad,
                'precio_unitario' => $detalle->precio_unitario
            ]);
        }

        DB::commit();

        return redirect()->route('pedidos.index')->with('status', 'Pedido repetido correctamente.');
    }
}