<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
         Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('casa_id')->unsigned();
            $table->string('codperi');
            $table->integer('anio');
            $table->float('monto');
            $table->integer('forma_pago_id')->unsigned();
            $table->integer('cuenta_id')->unsigned();
            $table->string('referencia');
            $table->integer('user_id')->unsigned();
            $table->date('fecha_pago');
            $table->boolean('confirmado');
            $table->boolean('rechazado');
            $table->timestamps();


             $table->foreign('casa_id')->references('id')->on('casas')->onUpdate('cascade');
             $table->foreign('anio')->references('aniofiscal')->on('anio_fiscal');
             $table->foreign('cuenta_id')->references('id')->on('cuentas_bancos')->onUpdate('cascade');
             $table->foreign('forma_pago_id')->references('id')->on('forma_pagos')->onUpdate('cascade');
             $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
