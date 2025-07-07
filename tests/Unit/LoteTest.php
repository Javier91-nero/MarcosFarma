<?php

namespace Tests\Unit;

use App\Models\Producto;
use App\Models\Lote;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_lote_para_producto()
    {
        $producto = Producto::factory()->create();

        $lote = Lote::create([
            'id_producto' => $producto->id_producto,
            'nro_lote' => 'L123',
            'fecha_vencimiento' => now()->addYear(),
            'cantidad' => 50
        ]);

        $this->assertDatabaseHas('lotes', ['nro_lote' => 'L123']);
    }
}