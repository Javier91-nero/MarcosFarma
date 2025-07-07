<?php

namespace Database\Factories;

use App\Models\Cuenta;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class CuentaFactory extends Factory
{
    protected $model = Cuenta::class;

    public function definition()
    {
        return [
            'id_cliente' => Cliente::factory(),
            'contrasena' => bcrypt('password123'),
            'validacion' => 1,
            'codigo_verificacion' => $this->faker->regexify('[A-Z0-9]{8}'),
            'codigo_enviado_en' => now(),
        ];
    }
}