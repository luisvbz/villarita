<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HijosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hijos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('propietario_id')->unsigned();
            $table->integer('cedula')->nullable()->unique();
            $table->string('apellidos');
            $table->string('nombre');
            $table->date('fecnac');    
            $table->string('sexo', 1);    
            $table->string('grado_estudio');    
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
        Schema::dropIfExists('hijos');
    }
}
