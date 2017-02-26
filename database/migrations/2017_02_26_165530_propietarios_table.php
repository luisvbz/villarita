<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropietariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('propietarios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cedula')->unique();
            $table->string('apellidos');
            $table->string('nombres');
            $table->date('fecnac');
            $table->string('sexo', 1);
            $table->string('profesion');
            $table->string('empresa')->nullable();
            $table->string('telefono1')->unique();
            $table->string('telefono2')->nullable();
            $table->string('telefono3')->nullable();
            $table->string('email')->unique();
            $table->boolean('inquilino');
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
        Schema::dropIfExists('propietarios');
    }
}
