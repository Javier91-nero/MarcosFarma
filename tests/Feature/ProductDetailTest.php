<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Producto;
use App\Models\Lote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_detalles_producto_y_lotes()
    {
        // Crear un producto de prueba
        $producto = Producto::factory()->create([
            'nombre' => 'Producto Test',
            'precio' => 25.50,
            'descripcion' => 'Descripción de prueba',
            'oferta' => true,
            'imagen' => 'storage/productos/test.jpg',
        ]);

        // Crear algunos lotes asociados
        Lote::factory()->create([
            'id_producto' => $producto->id_producto,
            'nro_lote' => 'L001',
            'fecha_vencimiento' => now()->addMonths(6),
            'cantidad' => 10,
        ]);
        Lote::factory()->create([
            'id_producto' => $producto->id_producto,
            'nro_lote' => 'L002',
            'fecha_vencimiento' => now()->addMonths(12),
            'cantidad' => 20,
        ]);

        // Visitar la ruta de detalles del producto
        $response = $this->get(route('product.show', $producto->id_producto));

        // Comprobar que la página carga correctamente
        $response->assertStatus(200);

        // Verificar que muestra nombre, precio, descripción, oferta
        $response->assertSeeText('Producto Test');
        $response->assertSeeText('S/ 25.50');
        $response->assertSeeText('Descripción de prueba');
        $response->assertSeeText('Sí');  // Por el badge de oferta

        // Verificar que muestra los lotes
        $response->assertSeeText('L001');
        $response->assertSeeText('L002');

        // Verificar que muestra la tabla de lotes
        $response->assertSee('<table', false);
    }
}
