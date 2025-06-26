<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteTable extends Migration
{
    public function up()
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->string('nombre', 20);
            $table->string('apellido', 20);
            $table->char('dni', 8)->unique();
            $table->string('telefono', 9)->nullable();
            $table->string('correo', 50)->unique();
            $table->enum('rol', ['admin', 'cliente'])->default('cliente');
            $table->string('token', 10)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cliente');
    }
}