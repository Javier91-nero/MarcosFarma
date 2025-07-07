<?php

namespace Tests\Unit;

use App\Models\Cliente;
use App\Models\Cuenta;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CuentaTest extends TestCase
{
    use RefreshDatabase;

    public function test_creacion_cuenta_con_cliente()
    {
        $cliente = Cliente::factory()->create();

        $cuenta = Cuenta::create([
            'id_cliente' => $cliente->id_cliente,
            'contrasena' => bcrypt('password123'),
            'validacion' => 1,
            'codigo_verificacion' => 'ABC12345'
        ]);

        $this->assertDatabaseHas('cuenta', ['id_cliente' => $cliente->id_cliente]);
    }
}