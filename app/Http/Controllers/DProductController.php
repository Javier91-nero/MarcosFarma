<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Lote;

class DProductController extends Controller
{
    public function index()
    {
        $productos = Producto::with('lotes')->get();
        return view('dashboard.products', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'imagen' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'oferta' => 'nullable|boolean',
            'nro_lote' => 'required|string',
            'fecha_vencimiento' => 'required|date|after_or_equal:today',
            'cantidad' => 'required|integer|min:1'
        ]);

        $imagen = $request->file('imagen');
        $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
        $imagen->move(public_path('productos_img'), $nombreImagen);

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'descripcion' => $request->descripcion,
            'imagen' => 'productos_img/' . $nombreImagen,
            'oferta' => $request->oferta ?? 0,
        ]);

        Lote::create([
            'id_producto' => $producto->id_producto,
            'nro_lote' => $request->nro_lote,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'cantidad' => $request->cantidad
        ]);

        return redirect()->route('product.index')->with('success', 'Producto y lote registrados correctamente');
    }

    public function show($id)
    {
        $producto = Producto::with('lotes')->findOrFail($id);
        return response()->json($producto);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'oferta' => 'nullable|boolean',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;
        $producto->oferta = $request->has('oferta') ? 1 : 0;

        // Manejar imagen si se sube una nueva
        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen && file_exists(public_path($producto->imagen))) {
                unlink(public_path($producto->imagen));
            }

            $imagen = $request->file('imagen');
            $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
            $imagen->move(public_path('productos_img'), $nombreImagen);

            $producto->imagen = 'productos_img/' . $nombreImagen;
        }

        $producto->save();

        return redirect('/dashboard')->with('success', 'Producto actualizado correctamente.');
    }

    public function details($id)
    {
        $producto = Producto::with('lotes')->findOrFail($id);
        return view('dashboard.product_details', compact('producto'));
    }
}