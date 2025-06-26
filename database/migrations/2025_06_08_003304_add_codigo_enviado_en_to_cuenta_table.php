<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodigoEnviadoEnToCuentaTable extends Migration
{
    public function up()
    {
        Schema::table('cuenta', function (Blueprint $table) {
            $table->timestamp('codigo_enviado_en')->nullable()->after('codigo_verificacion');
        });
    }

    public function down()
    {
        Schema::table('cuenta', function (Blueprint $table) {
            $table->dropColumn('codigo_enviado_en');
        });
    }
}
