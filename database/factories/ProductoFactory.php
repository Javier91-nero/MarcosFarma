<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Producto;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(),
            'precio' => $this->faker->randomFloat(2, 10, 200),
            'descripcion' => $this->faker->sentence(),
            'imagen' => 'default.png',
            'oferta' => $this->faker->boolean(),
        ];
    }
}