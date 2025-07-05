<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Producto;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function realizar(Request $request)
    {
        // Validación
        $request->validate([
            'numero_tarjeta' => 'required|digits:16',
            'metodo_pago' => 'required|in:tarjeta',
            'total' => 'required|numeric|min:0.1'
        ]);

        $cliente = session('cliente');
        if (!$cliente) {
            return redirect('/login')->with('error', 'Debe iniciar sesión para pagar.');
        }

        $carrito = session('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'El carrito está vacío.');
        }

        DB::beginTransaction();

        try {
            // 1. Crear el pedido
            $pedido = Pedido::create([
                'id_cliente' => $cliente->id_cliente,
                'total' => $request->total,
                'metodo_pago' => $request->metodo_pago,
                'numero_tarjeta_cifrada' => encrypt($request->numero_tarjeta),
                'fecha' => Carbon::now()
            ]);

            // 2. Detalles del pedido y actualización de stock
            foreach ($carrito as $item) {
                // Verificar stock actual
                $lote = DB::table('lotes')
                    ->where('id_producto', $item['id'])
                    ->where('cantidad', '>=', $item['cantidad'])
                    ->orderBy('fecha_vencimiento')
                    ->first();

                if (!$lote) {
                    throw new \Exception("Stock insuficiente para el producto: " . $item['nombre']);
                }

                DetallePedido::create([
                    'id_pedido' => $pedido->id_pedido,
                    'id_producto' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio']
                ]);

                // Descontar del lote
                DB::table('lotes')
                    ->where('id_lote', $lote->id_lote)
                    ->decrement('cantidad', $item['cantidad']);
            }

            DB::commit();

            // Limpiar carrito
            Session::forget('carrito');

            // Redirigir a la factura
            return redirect()->route('checkout.factura', ['id' => $pedido->id_pedido]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    public function factura($id)
    {
        $pedido = Pedido::with(['cliente', 'detalles.producto'])->findOrFail($id);

        return view('carrito.factura', compact('pedido'));
    }
}
