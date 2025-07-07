<?php

namespace Tests\Unit;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\DetallePedido;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DetallePedidoTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_detalle_pedido()
    {
        $cliente = Cliente::factory()->create();
        $pedido = Pedido::factory()->create(['id_cliente' => $cliente->id_cliente]);
        $producto = Producto::factory()->create();

        $detalle = DetallePedido::create([
            'id_pedido' => $pedido->id_pedido,
            'id_producto' => $producto->id_producto,
            'cantidad' => 2,
            'precio_unitario' => 20.00
        ]);

        $this->assertDatabaseHas('detalle_pedido', ['cantidad' => 2]);
    }
}
