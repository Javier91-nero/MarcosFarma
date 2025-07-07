<?php

namespace Tests\Unit;

use App\Models\Producto;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_producto()
    {
        $producto = Producto::create([
            'nombre' => 'Paracetamol',
            'precio' => 10.50,
            'descripcion' => 'Analgésico',
            'imagen' => 'imagen.jpg',
            'oferta' => true
        ]);

        $this->assertDatabaseHas('productos', ['nombre' => 'Paracetamol']);
    }
}