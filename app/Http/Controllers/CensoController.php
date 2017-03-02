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

    	//return $vehiculos;
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
    			$vehiculo->anio = $v['anio'];
    			$vehiculo->placa = $v['placa'];
    			$vehiculo->save();
    		}
    	}

    	return response()->json(['save' => true, 'msj' => 'El registro fue hecho exitosamente']);

    }

    public function updateCasa($id, Request $request){

        $casa = $request->input('casa');
        $propietario = $request->input('propietario');
        $conyuge = $request->input('conyuge');
        $hijos = $request->input('hijos');
        $vehiculos = $request->input('vehiculos');


        //return $casa['id'];
        //Actualizar la casa 
        $c = Casa::find($casa['id']);
        $c->numero = $casa['numero'];
        $c->calle = $casa['calle'];
        $csave = $c->save();

        if(!$csave){

            return response()->json(['save' => false, 'msj' => 'Ocurrio un error con el numero de la casa!']);
        }

        //Actualizar informacion del propietario

        $p = Propietario::find($propietario['id']);
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

        if($request->input('hasConyuge')){

            if(count($conyuge) == 14){
                $cyg = Conyuge::find($conyuge['id']);
                $cyg->cedula = $conyuge['cedula'];
                $cyg->apellidos = $conyuge['apellidos'];
                $cyg->nombres = $conyuge['nombres'];
                $cyg->fecnac = date('Y-m-d', strtotime($conyuge['fecnac']));
                $cyg->sexo = $conyuge['sexo'];
                $cyg->profesion = $conyuge['profesion'];
                $cyg->empresa = $conyuge['empresa'];
                $cyg->telefono1 = $conyuge['telefono1'];
                $cyg->telefono2 = $conyuge['telefono2'];
                $cyg->telefono3 = $conyuge['telefono3'];
                $cygsave = $cyg->save();

                if(!$cygsave){

                     return response()->json(['save' => false, 'msj' => 'Ocurrio un error al guardar el conyuge!']);
                }

            }else{
                $cyg = new Conyuge;
                $cyg->propietario_id = $p->id;
                $cyg->cedula = $conyuge['cedula'];
                $cyg->apellidos = $conyuge['apellidos'];
                $cyg->nombres = $conyuge['nombres'];
                $cyg->fecnac = date('Y-m-d', strtotime($conyuge['fecnac']));
                $cyg->sexo = $conyuge['sexo'];
                $cyg->profesion = $conyuge['profesion'];
                $cyg->empresa = $conyuge['empresa'];
                if(count($conyuge) == 8){
                    $cyg->telefono1 = $conyuge['telefono1'];
                }elseif(count($conyuge) == 9){
                    $cyg->telefono1 = $conyuge['telefono1'];
                    $cyg->telefono2 = $conyuge['telefono2'];
                }elseif(count($conyuge) == 10){
                    $cyg->telefono1 = $conyuge['telefono1'];
                    $cyg->telefono2 = $conyuge['telefono2'];
                    $cyg->telefono3 = $conyuge['telefono3'];
                }
                
                $cygsave = $cyg->save();
            }
        }else{

            if(count($conyuge) == 0){

            }else{
                $cyg = Conyuge::find($conyuge['id']);
                $cyg->delete();
            }
        
        }

        //actualizar informacion de los hijos

        if($request->input('hasHijos')){

            foreach ($hijos as $h) {
                
                $verificarHijo = Hijo::where('cedula', $h['cedula'])->get();

                if(count($verificarHijo) > 0){
                    $hijo = Hijo::find($h['id']);
                    $hijo->cedula = $h['cedula'];
                    $hijo->apellidos = $h['apellidos'];
                    $hijo->nombre = $h['nombre'];
                    $hijo->fecnac = date('Y-m-d', strtotime($h['fecnac']));
                    $hijo->sexo = $h['sexo'];
                    $hijo->grado_estudio = $h['grado_estudio'];
                    $hijo->save();
                }else{

                    $hijo = new Hijo;
                    $hijo->propietario_id = $p->id;
                    $hijo->cedula = $h['cedula'];
                    $hijo->apellidos = $h['apellidos'];
                    $hijo->nombre = $h['nombre'];
                    $hijo->fecnac = date('Y-m-d', strtotime($h['fecnac']));
                    $hijo->sexo = $h['sexo'];
                    $hijo->grado_estudio = $h['grado_estudio'];
                    $hijo->save();
                }

            }
        }

        //actualizar o agregar vehiculos

        if($request->input('hasVehiculos')){


        foreach ($vehiculos as $v) {
            
            $verificarVehiculo = Vehiculo::where('placa', $v['placa'])->get();

            if(count($verificarVehiculo) > 0){

                    $vehiculo = Vehiculo::find($v['id']);
                    $vehiculo->marca = $v['marca'];
                    $vehiculo->modelo = $v['modelo'];
                    $vehiculo->anio = $v['anio'];
                    $vehiculo->placa = $v['placa'];
                    $vehiculo->save();

                }else{
                    $vehiculo = new Vehiculo;
                    $vehiculo->propietario_id = $p->id;
                    $vehiculo->marca = $v['marca'];
                    $vehiculo->modelo = $v['modelo'];
                    $vehiculo->anio = $v['anio'];
                    $vehiculo->placa = $v['placa'];
                    $vehiculo->save();
                }
            }
        }

        return response()->json(['save' => true, 'msj' => 'El registro fue actualizado exitosamente']);
    }
}
