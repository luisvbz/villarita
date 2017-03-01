<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Propietario;
use App\Conyuge;
use App\Casa;
use App\Hijo;
use App\Vehiculo;

class CensoController extends Controller
{
    public function getAll()
    {

    	$propietarios = Casa::with('propietario')->orderBy('numero', 'ASC')->get();

    	return $propietarios->toJson();
    }

    public function pdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test</h1>');
        return $pdf->stream();
    }

    public function getCasa($id)
    {

    	//$propietario = Propietario::with('casa', 'conyuge', 'hijos', 'vehiculos')->where('id', $id)->get();

        $propietario = Propietario::with('casa', 'conyuge', 'hijos', 'vehiculos')
                                        ->whereHas('casa', function($q) use($id){
                                            $q->where('numero', $id);
                                        })->get();


    	return $propietario->toJson();
    }

    public function save(Request $request)
    {
    	$propietario = $request->input('propietario');
    	$conyuge = $request->input('conyuge');
    	$hijos = $request->input('hijos');
    	$vehiculos = $request->input('vehiculos');

    	//return $hijos;
    	//verificar si la casa esta registrada

    	$verificarCasa = Casa::where('numero', $propietario['casa'])->get();

    	if(count($verificarCasa) > 0){

    		return response()->json(['save' => false, 'msj' => 'Ya esta casa está registrada']);
    	}

    	//verificar propietario

    	$verificarPropietario = Propietario::where('cedula', $propietario['cedula'])->get();

    	if(count($verificarPropietario) > 0){

    		return response()->json(['save' => false, 'msj' => 'Ya esta persona esta registrada']);

    	}

    	//Verificar que la cedula de algun hijo no este repetida
    	foreach ($hijos as $h) {

    		$verificarHijo = Hijo::where('cedula', $h['cedula'])->get();

    		if(count($verificarHijo) > 0){

    			return response()->json(['save' => false, 'msj' => 'La cedula '.$h['cedula'].' esta registrada en hijos']);

    		}
    	}

    	//verificar placas de vehiculos 

    	foreach ($vehiculos as $v) {
    		
    		$verificarVehiculo = Vehiculo::where('placa', $v['placa'])->get();

    		if(count($verificarVehiculo) > 0){

    			return response()->json(['save' => false, 'msj' => 'Ya un vehiculo con las placas '.$v['placa'].' esta registrado']);

    		}
    	}
    	


    	//Guardar datos del propietario

    	$p = new Propietario;
    	$p->cedula = $propietario['cedula'];
    	$p->apellidos = $propietario['apellidos'];
    	$p->nombres = $propietario['nombres'];
    	$p->fecnac = date('Y-m-d', strtotime($propietario['fecnac']));
    	$p->sexo = $propietario['sexo'];
    	$p->profesion = $propietario['profesion'];
    	$p->empresa = $propietario['empresa'];
    	$p->telefono1 = $propietario['tel1'];
    	$p->telefono2 = $propietario['tel2'];
    	$p->telefono3 = $propietario['tel3'];
    	$p->email = $propietario['email'];
    	$p->inquilino = $propietario['inquilino'];
    	$psave = $p->save();

    	if(!$psave){

    		return response()->json(['save' => false, 'msj' => 'Ocurrio un error al guardat el propietario']);

    	}

    	//Guardar datos de la casa

    	$casa = new Casa;
    	$casa->numero = $propietario['casa'];
    	$casa->calle = $propietario['calle'];
    	$casa->propietario_id = $p->id;
    	$casa->save();

    	//Verificar si viene con conyuge

    	if($request->input('hasConyuge')){

    		$verificarConyuge = Conyuge::where('cedula', $conyuge['cedula'])->get();

    		if(count($verificarConyuge) > 0) {

    			return response()->json(['save' => false, 'msj' => 'La cedula del conyuge ya esta registrada']);
    		}

    		$cnyg = new Conyuge;
    		$cnyg->propietario_id = $p->id;
    		$cnyg->cedula = $conyuge['cedula'];
    		$cnyg->apellidos = $conyuge['apellidos'];
    		$cnyg->nombres = $conyuge['nombres'];
    		$cnyg->fecnac = date('Y-m-d', strtotime($conyuge['fecnac']));
    		$cnyg->sexo = $conyuge['sexo'];
    		$cnyg->profesion = $conyuge['profesion'];
    		$cnyg->empresa = $conyuge['empresa'];
    		$cnyg->telefono1 = $conyuge['tel1'];
    		$cnyg->telefono2 = $conyuge['tel2'];
    		$cnyg->telefono3 = $conyuge['tel3'];
    		$cnyg->save();
    	}

    	if($request->input('hasHijos')){
    		
    		foreach ($hijos as $h) {
    			$hijo = new Hijo;
    			$hijo->propietario_id = $p->id;
    			$hijo->cedula = $h['cedula'];
    			$hijo->apellidos = $h['apellidos'];
    			$hijo->nombre = $h['nombres'];
    			$hijo->fecnac = date('Y-m-d', strtotime($h['fecnac']));
    			$hijo->sexo = $h['sexo'];
    			$hijo->grado_estudio = $h['grado'];
    			$hijo->save();
    		}
    	}

    	if($request->input('hasVehiculos')){
    		
    		foreach ($vehiculos as $v) {
    			$vehiculo = new Vehiculo;
    			$vehiculo->propietario_id = $p->id;
    			$vehiculo->marca = $v['marca'];
    			$vehiculo->modelo = $v['modelo'];
    			$vehiculo->año = $v['anio'];
    			$vehiculo->placa = $v['placa'];
    			$vehiculo->save();
    		}
    	}

    	return response()->json(['save' => true, 'msj' => 'El registro fue hecho exitosamente']);

    }
}
