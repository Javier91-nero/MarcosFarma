<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition()
    {
        return [
            'id_cliente' => Cliente::factory(),
            'total' => $this->faker->randomFloat(2, 10, 500),
            'metodo_pago' => 'tarjeta',
            'numero_tarjeta_cifrada' => encrypt('4111111111111111'),
        ];
    }
}