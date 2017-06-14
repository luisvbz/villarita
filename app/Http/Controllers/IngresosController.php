<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingreso;
use App\Casa;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;
use App\Pago;
use App\Cuenta;
use App\Sms;
class IngresosController extends Controller
{
    public function getIngresos()
    {
    	/*$ingresos =  Ingreso::with('casa')
    						->select('casa_id', DB::raw("SUM(deuda) as deuda"), DB::raw("SUM(pago) as pago"))
    						->groupBy('casa_id')
    						->orderBy('casa_id', 'ASC')
    						->get();*/

    	$ingresos = DB::select("SELECT a.id, a.numero, b.id as p_id, b.apellidos, b.nombres, b.telefono1 as tlf, sum(c.monto) as monto 
								FROM casas a
								INNER JOIN propietarios b ON b.id = a.propietario_id
								LEFT JOIN ingresos c ON c.casa_id = a.id
								GROUP BY a.id, a.numero, b.id, b.apellidos, b.nombres, b.telefono1
								ORDER BY a.numero ASC");
    	$data = array();

    	foreach ($ingresos as $i) {

                $saldo = ($i->monto);     

    		
    		array_push($data, array('casa_id' => $i->id,
    								'numero' => $i->numero,
    						   'propietario_id' => $i->p_id,
                               'propietario' => $i->apellidos.', '.$i->nombres,
    						   'telefono' => $i->tlf,
    						   'saldo' => number_format($saldo, 2,',', '.')));
    	}


    	return response()->json($data);
    }

    public function generarCobro(Request $request)
    {
    	$cobro = $request->input('cobro');
        $sms = $request->input('sms');
        $casas = array();

        //$mensaje = 'Hola Luis, se realizó el cobro de: '.strtoupper($request->input('nomtipocobro')).' de '.strtoupper($request->input('periodo')).' del '.$request->input('anio').', por el monto de '.$cobro['monto'].'. Verifique su estado de cuenta en condominiovillarita.com.ve';

       // return strlen($mensaje);
        $user = JWTAuth::parseToken()->authenticate();

        $totalCobros = Ingreso::all()->count();

        $arrayTelefonos = array();

    	foreach ($cobro['casas'] as $key => $value) {

            $verificar = Ingreso::where('codperi', $cobro['codperi'])
                                  ->where('anio', $cobro['anio'])
                                  ->where('casa_id', $value)
                                  ->where('tipo_ingreso_id', $cobro['tipo_ingreso'])
                                  ->where('tipo', 'D')
                                  ->get();
            //return $verificar;

            if(count($verificar) > 0){
                $casa = Casa::find($value);

                array_push($casas, array($casa->numero));

            }else{

                $new = new Ingreso;
                $new->casa_id = $value;
                $new->tipo_ingreso_id = $cobro['tipo_ingreso'];
                $new->tipo = 'D';
                $new->anio = $cobro['anio'];
                $new->codperi = $cobro['codperi'];
                $new->monto = -$cobro['monto'];
                $new->user_id = $user->id;
                $save = $new->save();


                if($save == true){

                    $casa = Casa::find($value);

                    $apellidos = explode(" ", $casa->propietario->apellidos);
                    $nombres = explode(" ", $casa->propietario->nombres);

                    $mensaje = 'Hola '.$nombres[0].', se realizó el cobro de: '.strtoupper($request->input('nomtipocobro')).' de '.strtoupper($request->input('periodo')).' del '.$request->input('anio').', por '.$cobro['monto'].'. Verifique su estado de cuenta.';

                    array_push($arrayTelefonos, array('telefono' => $casa->propietario->telefono1,
                                                      'mensaje' => $mensaje));
                }  
            }
    		
    	}

        if($sms == true){
            foreach ($arrayTelefonos as $a) {
               $newSms = new Sms();
                $parameters = array();
                $parameters['cuenta_token']     = '5efdf4ab22b5eae853c6304cde484f6b2cac3fa5';
                $parameters['aplicacion_token'] = 'c7c974fb3bffba1197ca6abe614b133db31c9c6a';
                $parameters['telefono']         = $a['telefono'];
                $parameters['mensaje']          = $a['mensaje'];
            
                $newSms->enviar($parameters, true);
            }
            
        }

        $diferencia = Ingreso::all()->count();

        if($totalCobros == $diferencia){
            $save = FALSE;
        }else{
            $save = TRUE;
        }

    	return response()->json(array('save' => $save, 
                                       'msj' => 'Las cuotas se generaron correctamente',
                                       'casas' => $casas));

    }

    public function detalleSms(){
            $detalles = new Sms();
            $parameters = array();
            $parameters['cuenta_token']     = '5efdf4ab22b5eae853c6304cde484f6b2cac3fa5';
            $parameters['aplicacion_token'] = 'c7c974fb3bffba1197ca6abe614b133db31c9c6a';
            $detalles->detalles($parameters, true);


    }

    public function PagoPendientes($casa_id)
    {
        $c = Casa::where('numero', $casa_id)->get();
        $Pendientes = Ingreso::with('tipoIngreso', 'periodo')
                            ->where('casa_id', $c[0]->id)
                            ->where('confirmado', 0)
                            ->orderBy('anio', 'DESC')
                            ->orderBy('codperi', 'DESC')
                            ->get();

        return $Pendientes->toJson();
    }

    public function consolidacion(){
        
    }

    public function getPagos(){


        $pagos = Pago::with('cuenta','formapago','user','casa')->orderBy('created_at', 'DESC')->paginate(12);

        $porConfirmar = Pago::where('confirmado',0)->count(); 

        return response()->json(array('pagos' => $pagos, 'cuantos' => $porConfirmar));
    }

     public function procesarPago(Request $request)
    {
        $id_pago = $request->input('id');

        $p = Pago::find($id_pago);
        $p->confirmado = true;
        $p->save();

            $pago = new Ingreso;
            $pago->casa_id = $p->casa_id;
            $pago->forma_pago_id = $p->forma_pago_id;
            $pago->cuenta_id = $p->cuenta->id;
            $pago->referencia = $p->referencia;
            $pago->fecha_pago = $p->fecha_pago;
            $pago->monto = $p->monto;
            $pago->tipo = 'C';
            $pago->tipo_ingreso_id = $p->forma_pago_id;
            $pago->anio = $p->anio;
            $pago->codperi = $p->codperi;
            $pago->user_id = $p->user_id;
            $psave = $pago->save();

            //return $pagos['tipoPago'];
            if($psave == true){

                $cuenta = Cuenta::find($p->cuenta->id);
                $cuenta->disponible = $cuenta->disponible + $p->monto;
                $cuenta->save();
            }


        return response()->json(array('save' => true));
    }


}
