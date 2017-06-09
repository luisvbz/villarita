<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Propietario;
use App\Conyuge;
use App\Casa;
use App\Hijo;
use App\Vehiculo;
use App\Ingreso;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use PDF;
use App\Cuenta;
use App\Cne;
use App\Pago;
use App\User;

class CensoController extends Controller
{
    
    public function getAll()
    {

    	$propietarios = Casa::with('propietario')->orderBy('numero', 'ASC')->get();

    	return $propietarios->toJson();
    }

    public function getEstadoCuenta($casa, $anio){

        $c = Casa::where('numero', $casa)->get();


        if($anio != 0){
            $estCuenta = Ingreso::with('tipoIngreso', 'periodo','cuenta')
                            ->where('casa_id', $c[0]->id)
                            ->where('anio', $anio)
                            ->orderBy('created_at', 'ASC')
                            ->get();
        }else{
            $estCuenta = Ingreso::with('tipoIngreso', 'periodo','cuenta')
                            ->where('casa_id', $c[0]->id)
                            ->orderBy('created_at', 'ASC')
                            ->get();
        }
        
                            
        return $estCuenta->toJson();
    }

    public function getPagos(){

        $user = JWTAuth::parseToken()->authenticate();

        $pagos = Pago::with('cuenta','formapago','user')->where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(12);

        return $pagos->toJson();
    }

    public function registrarPago(Request $request){

            $user = JWTAuth::parseToken()->authenticate();
            $data = $request->input('data');
            $casa = Casa::where('numero', $user->casa)->get();

            $pago = new Pago;
            $pago->casa_id = $casa[0]->id;
            $pago->codperi = '0'.date('m');
            $pago->anio = date('Y');
            $pago->monto = $data['monto'];
            $pago->forma_pago_id = $data['tipoPago'];
            $pago->cuenta_id = $data['cuenta'];
            $pago->referencia = $data['referencia'];
            $pago->user_id = $user->id;
            $pago->fecha_pago = $data['fecha'];
            $pago->confirmado = false;
            $pago->rechazado = false;
            
            try {

                $pago->save();

                $miPago = Pago::find($pago->id)->with('cuenta','user');
                return response()->json(array('save' => true, 'pago' => $pago)); 

            } catch (Exception $e) {

                return response()->json(array('save' => false, 'pago' => ''));    
            }

    }

    public function pagarDeuda(Request $request)
    {
        $pagos = $request->input('pagos');

       // return $request->all();
        $user = JWTAuth::parseToken()->authenticate();
            $pago = new Ingreso;
            $pago->casa_id = $request->input('casa_id');
            $pago->forma_pago_id = $pagos['tipoPago'];
            $pago->cuenta_id = $pagos['cuenta'];
            $pago->referencia = $pagos['ref'];
            $pago->fecha_pago = date('Y-m-d');
            $pago->monto = $pagos['monto'];
            $pago->tipo = 'C';
            $pago->tipo_ingreso_id = $pagos['tipoPago'];
            $pago->anio = $request->input('anio');;
            $pago->codperi = '0'.date('m');
            $pago->user_id = $user->id;
            $psave = $pago->save();

            //return $pagos['tipoPago'];
            if($psave == true){

                $cuenta = Cuenta::find($pagos['cuenta']);
                $cuenta->disponible = $cuenta->disponible + $pagos['monto'];
                $cuenta->save();
            }


        return response()->json(array('save' => true));
    }

    public function pdf(Request $request){

        $id_casa = $request->input('casa_id');
        $anio = $request->input('anio');
        $propietario = $request->input('propietario');
        $numero = $request->input('numero');

         if($anio != 0){
            $estCuenta = Ingreso::with('tipoIngreso', 'periodo','cuenta')
                            ->where('casa_id',$id_casa)
                            ->where('anio', $anio)
                            ->orderBy('created_at', 'ASC')
                            ->get();
        }else{
            $estCuenta = Ingreso::with('tipoIngreso', 'periodo','cuenta')
                            ->where('casa_id', $id_casa)
                            ->orderBy('created_at', 'ASC')
                            ->get();
        }


        $pdf =  PDF::loadView('reportes.estcuenta', compact('estCuenta', 'propietario','numero'));
        //$pdf->loadView('reportes.estcuenta', $estcuenta);
        return $pdf->download();
    }

    public function getCasa($id)
    {

    	//$propietario = Propietario::with('casa', 'conyuge', 'hijos', 'vehiculos')->where('id', $id)->get();

        $propietario = Propietario::with('casa', 'conyuge', 'hijos', 'vehiculos')
                                        ->whereHas('casa', function($q) use($id){
                                            $q->where('numero', $id);
                                        })->get();

        $saldo = \DB::select("SELECT sum(c.monto) as monto 
                                FROM casas a
                                LEFT JOIN ingresos c ON c.casa_id = a.id
                                WHERE a.numero = $id");

    	return response()->json(array('propietario' => $propietario, 'saldo' => $saldo[0]->monto));
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

        $ape = explode(" ", $p->apellidos);
        $nom = explode(" ", $p->nombres);

        $user = new User;
        $user->cedula = $p->cedula;
        $user->username = $p->cedula;
        $user->name = $nom[0].' '.$ape[0];
        $user->email = $p->email;
        $user->password = \Hash::make($p->cedula);
        $user->role_id = 3;
        $user->casa = $casa->numero;
        $user->save();

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

    public function buscarCne($nac, $cedula){

        $result = Cne::buscar($nac, $cedula);

        return $result;
    }
}
