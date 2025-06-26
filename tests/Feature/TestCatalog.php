<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Producto;  // Aquí importas Producto, no Product

class TestCatalog extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_de_catalogo_carga_correctamente()
    {
        Producto::factory()->create([
            'nombre' => 'Producto de prueba',
            'precio' => 100,
            'descripcion' => 'Descripción del producto de prueba',
            'imagen' => 'default.png',
            'oferta' => true,
        ]);

        $response = $this->get('/catalog');

        $response->assertStatus(200);
        $response->assertSee('Catálogo de Productos');
        $response->assertSee('Buscar por nombre de producto');
        $response->assertSee('Solo en oferta');
        $response->assertSee('Menor a S/ 50');
        $response->assertSee('Agregar al Carrito');
    }
}