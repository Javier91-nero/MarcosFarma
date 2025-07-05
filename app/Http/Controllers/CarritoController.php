<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;

class CarritoController extends Controller
{
    public function agregar(Request $request)
    {
        $producto = Producto::findOrFail($request->input('id_producto'));
        $id = $producto->id_producto;
        $cantidadSolicitada = (int) $request->input('cantidad', 1);

        // Stock disponible (lotes vigentes)
        $stockDisponible = DB::table('lotes')
            ->where('id_producto', $id)
            ->where('fecha_vencimiento', '>=', now())
            ->sum('cantidad');

        $carrito = session()->get('carrito', []);
        $cantidadEnCarrito = isset($carrito[$id]) ? $carrito[$id]['cantidad'] : 0;

        $totalSolicitado = $cantidadSolicitada + $cantidadEnCarrito;

        if ($totalSolicitado > $stockDisponible) {
            return redirect()->back()->with('error', 'Stock insuficiente. Solo hay ' . $stockDisponible . ' unidades disponibles.');
        }

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'] += $cantidadSolicitada;
        } else {
            $carrito[$id] = [
                'id' => $producto->id_producto,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'imagen' => $producto->imagen,
                'cantidad' => $cantidadSolicitada,
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

        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'status' => 'error',
                'message' => 'Producto no encontrado.'
            ]);
        }

        // Stock disponible (lotes no vencidos)
        $stockDisponible = DB::table('lotes')
            ->where('id_producto', $id)
            ->where('fecha_vencimiento', '>=', now())
            ->sum('cantidad');

        if ($cantidad > $stockDisponible) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cantidad solicitada excede el stock. Solo hay ' . $stockDisponible . ' unidades disponibles.',
                'disponible' => $stockDisponible
            ]);
        }

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'] = $cantidad;
            session(['carrito' => $carrito]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function eliminar(Request $request)
    {
        $id = $request->input('id_producto');

        $carrito = session()->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session(['carrito' => $carrito]);
        }

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito');
    }
}