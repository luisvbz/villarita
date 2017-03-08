<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Ingresos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('ingresos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('casa_id')->unsigned();
            $table->integer('codperi');
            $table->integer('anio');
            $table->float('deuda');
            $table->float('pago');
            $table->integer('forma_pago_id')->nullable()->unsigned();
            $table->integer('cuenta_id')->nullable()->unsigned();
            $table->string('referencia')->nullable();
            $table->integer('user_id')->unsigned();
            $table->date('fecha_pago');
            $table->boolean('confirmado');
            $table->timestamps();


             $table->foreign('casa_id')->references('id')->on('casas')->onUpdate('cascade');
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
        Schema::dropIfExists('ingresos');
    }
}
