<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class CarritoController extends Controller
{
    public function agregar(Request $request)
    {
        $productoId = $request->input('id_producto');
        $cantidad = $request->input('cantidad', 1);

        $producto = Producto::findOrFail($productoId);

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$productoId])) {
            $carrito[$productoId]['cantidad'] += $cantidad;
        } else {
            $carrito[$productoId] = [
                'id' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'imagen' => $producto->imagen,
                'cantidad' => $cantidad
            ];
        }

        session()->put('carrito', $carrito);

        return redirect()->back()->with('status', 'Producto agregado al carrito.');
    }

    public function mostrar()
    {
        $carrito = session('carrito', []);
        return view('carrito.index', compact('carrito'));
    }

    public function eliminar($id)
    {
        $carrito = session()->get('carrito', []);
        unset($carrito[$id]);
        session()->put('carrito', $carrito);
        return redirect()->route('carrito.mostrar')->with('status', 'Producto eliminado del carrito.');
    }

    public function vaciar()
    {
        session()->forget('carrito');
        return redirect()->route('carrito.mostrar')->with('status', 'Carrito vaciado correctamente.');
    }
}
