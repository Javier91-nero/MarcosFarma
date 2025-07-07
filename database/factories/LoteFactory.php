<?php

namespace Database\Factories;

use App\Models\Lote;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoteFactory extends Factory
{
    protected $model = Lote::class;

    public function definition()
    {
        return [
            'id_producto' => Producto::factory(),
            'nro_lote' => strtoupper($this->faker->bothify('L###??')),
            'fecha_vencimiento' => now()->addMonths(rand(3, 24)),
            'cantidad' => $this->faker->numberBetween(10, 100),
        ];
    }
}