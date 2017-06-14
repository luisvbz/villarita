<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vigilante;

class VigilantesController extends Controller
{
    public function getAll(){
    	$vigilantes = Vigilante::all();
    	
    	return $vigilantes->toJson();
    }

    public function guardar(Request $request){

        $data = $request->input('data');

        $v = new Vigilante;
        $v->cedula = $data['cedula'];
        $v->apellidos = $data['apellidos'];
        $v->nombres = $data['nombres'];
        $v->fecing = $data['fecing'];
        $v->fecegre = $data['fecing'];
        $v->activo = 1;
        $save = $v->save();

        return response()->json(array('save' => 'true', 'data' => $v));
    }

    public function cambiarStatus($id){

    	$v = Vigilante::find($id);

    	if($v->activo){
    		$v->activo = 0;
    		$v->save();
    	}else{
    		$v->activo = 1;
    		$v->save();
    	}

    	return response()->json(array('activo' => $v->activo));

    }
}
