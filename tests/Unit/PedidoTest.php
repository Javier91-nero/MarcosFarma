<?php

namespace Tests\Unit;

use App\Models\Cliente;
use App\Models\Pedido;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_pedido_con_datos()
    {
        $cliente = Cliente::factory()->create();

        $pedido = Pedido::create([
            'id_cliente' => $cliente->id_cliente,
            'total' => 100.00,
            'metodo_pago' => 'tarjeta',
            'numero_tarjeta_cifrada' => encrypt('4111111111111111')
        ]);

        $this->assertDatabaseHas('pedidos', ['id_cliente' => $cliente->id_cliente]);
    }
}