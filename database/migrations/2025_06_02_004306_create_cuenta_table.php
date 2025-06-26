<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuentaTable extends Migration
{
    public function up()
    {
        Schema::create('cuenta', function (Blueprint $table) {
            $table->id('id_cuenta');
            $table->unsignedBigInteger('id_cliente');
            $table->string('contrasena', 255);
            $table->tinyInteger('validacion')->default(0);
            $table->string('codigo_verificacion', 8)->nullable();
            $table->timestamps();

            $table->foreign('id_cliente')->references('id_cliente')->on('cliente')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cuenta');
    }
}
