<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestLogin extends TestCase
{
    public function test_la_pagina_de_login_carga_correctamente()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);

        $response->assertSee('Iniciar Sesión');
        $response->assertSee('Correo Electrónico');
        $response->assertSee('Contraseña');
        $response->assertSee('Ingresar');
        $response->assertSee('Registrarse');
        $response->assertSee('¿Olvidaste tu contraseña?');
    }
}