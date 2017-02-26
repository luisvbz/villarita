<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConyugeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conyuges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('propietario_id')->unsigned();
            $table->integer('cedula')->unique();
            $table->string('apellidos');
            $table->string('nombres');
            $table->date('fecnac');
            $table->string('sexo', 1);
            $table->string('profesion');
            $table->string('empresa')->nullable();
            $table->string('telefono1');
            $table->string('telefono2')->nullable();
            $table->string('telefono3')->nullable();
            $table->string('email')->unique();
            $table->timestamps();

            $table->foreign('propietario_id')->references('id')->on('propietarios')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conyuges');
    }
}
