<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Carbon\Carbon;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['lotes' => function($query) {
            $query->where('fecha_vencimiento', '>=', Carbon::now())
                  ->where('cantidad', '>', 0)
                  ->orderBy('fecha_vencimiento', 'asc');
        }])->get();

        // Lote más cercano asignado manualmente
        foreach ($productos as $producto) {
            $producto->lote_mas_cercano = $producto->lotes->first();
        }

        return view('product', compact('productos'));
    }
}
