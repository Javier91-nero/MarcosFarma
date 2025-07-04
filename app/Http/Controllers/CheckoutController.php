<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Pedido;
use App\Models\DetallePedido;

class CheckoutController extends Controller
{
    public function realizar(Request $request)
    {
        // Validar campos del formulario
        $request->validate([
            'numero_tarjeta' => 'required|digits:16',
            'metodo_pago' => 'required|in:tarjeta',
            'total' => 'required|numeric|min:0.1'
        ]);

        // Verificar que el cliente esté autenticado
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
                'total' => $request->total
            ]);

            // 2. Crear los detalles del pedido
            foreach ($carrito as $item) {
                DetallePedido::create([
                    'id_pedido' => $pedido->id_pedido,
                    'id_producto' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio']
                ]);

                // 3. Descontar stock del lote más cercano (si deseas manejar stock)
                DB::table('lotes')
                    ->where('id_producto', $item['id'])
                    ->where('cantidad', '>', 0)
                    ->orderBy('fecha_vencimiento')
                    ->limit(1)
                    ->decrement('cantidad', $item['cantidad']);
            }

            DB::commit();

            // 4. Vaciar carrito
            Session::forget('carrito');

            return redirect()->route('carrito.index')->with('success', '¡Pago realizado con éxito! Pedido registrado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }
}