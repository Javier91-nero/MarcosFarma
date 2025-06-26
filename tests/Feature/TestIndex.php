<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestIndex extends TestCase
{
    public function test_la_pagina_inicio_carga_correctamente()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('MarcosFarma');
        $response->assertSee('Tu farmacia de confianza');
        $response->assertSee('Ver Productos');
    }
}
