<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Propietario;
use App\Casa;

class CensoController extends Controller
{
    public function getAll(){

    	$propietarios = Casa::with('propietario')->orderBy('numero', 'ASC')->get();

    	return $propietarios->toJson();
    }

    public function getCasa($id){

    	$propietario = Propietario::with('casa', 'conyuge', 'hijos', 'vehiculos')->where('id', $id)->get();


    	return $propietario->toJson();
    }
}
