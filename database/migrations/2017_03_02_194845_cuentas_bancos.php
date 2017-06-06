<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CuentasBancos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuentas_bancos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('banco_id');
            $table->integer('cedula_titular');
            $table->string('titular');
            $table->string('email_titular');
            $table->string('tipo_cuenta',1);
            $table->float('capital_inicial');
            $table->float('disponible');
            $table->bigInteger('numero');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuentas_bancos'); 
    }
}
