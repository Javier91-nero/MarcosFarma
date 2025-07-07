<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName,
            'apellido' => $this->faker->lastName,
            'dni' => $this->faker->unique()->numerify('########'),
            'telefono' => $this->faker->numerify('9########'),
            'correo' => $this->faker->unique()->safeEmail,
            'rol' => 'cliente',
            'token' => strtoupper($this->faker->bothify('##########'))
        ];
    }
}
