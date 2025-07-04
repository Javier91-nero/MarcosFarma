<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class CarritoController extends Controller
{
    public function agregar(Request $request)
    {
        $producto = Producto::findOrFail($request->input('id_producto'));

        $carrito = session()->get('carrito', []);

        $id = $producto->id_producto;

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'] += $request->input('cantidad', 1);
        } else {
            $carrito[$id] = [
                'id' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'imagen' => $producto->imagen,
                'cantidad' => $request->input('cantidad', 1),
            ];
        }

        session(['carrito' => $carrito]);

        return redirect()->back()->with('success', 'Producto agregado al carrito');
    }

    public function mostrar()
    {
        $carrito = session()->get('carrito', []);
        return view('carrito.index', compact('carrito'));
    }

    public function actualizar(Request $request)
    {
        $id = $request->input('id_producto');
        $cantidad = max((int) $request->input('cantidad'), 1);

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'] = $cantidad;
            session(['carrito' => $carrito]);
        }

        return redirect()->route('carrito.mostrar')->with('success', 'Cantidad actualizada');
    }

    public function eliminar(Request $request)
    {
        $id = $request->input('id_producto');

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session(['carrito' => $carrito]);
        }

        return redirect()->route('carrito.mostrar')->with('success', 'Producto eliminado del carrito');
    }
}