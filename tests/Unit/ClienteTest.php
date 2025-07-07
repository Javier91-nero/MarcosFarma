<?php

namespace Tests\Unit;

use App\Models\Cliente;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    public function test_creacion_cliente_exitosa()
    {
        $cliente = Cliente::create([
            'nombre' => 'Juan',
            'apellido' => 'Perez',
            'dni' => '12345678',
            'telefono' => '987654321',
            'correo' => 'juan@example.com',
            'rol' => 'cliente',
            'token' => 'ABC1234567'
        ]);

        $this->assertDatabaseHas('cliente', ['dni' => '12345678']);
    }
}