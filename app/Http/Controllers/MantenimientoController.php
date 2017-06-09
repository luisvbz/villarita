<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AnioFiscal;
use App\Periodo;
use App\Cuenta;
use App\Banco;
use App\TipoIngreso;
use App\TipoEgreso;

class MantenimientoController extends Controller
{
    public function getAnios()
    {
    	$anios = AnioFiscal::orderBy('aniofiscal', 'DESC')->get();;
    	return $anios->toJson();
    }

    public function saveAnios(Request $request)
    {

    	$anio = $request->input('anio');
    	$desc = $request->input('desc');

    	$verifAnio = AnioFiscal::where('aniofiscal', $anio)->get();

    	if(count($verifAnio) > 0){

    		return response()->json(['save' => false, 'msj' => 'Este año ya esta registrado!', 'anio' => null]);
    	}

        if($anio > date('Y')){

            return response()->json(['save' => false, 'msj' => 'No puedes registrar un año posterior al actual!', 'anio' => null]);   
        }

    	$a = new AnioFiscal;
    	$a->aniofiscal = $anio;
    	$a->descripcion = "Año Fiscal ".$anio;
    	$a->activo = true;
    	$aniosave = $a->save();

    	if(!$aniosave){

    		return response()->json(['save' => false, 'msj' => 'Ocurrio un error al guardar el año fiscal', 'anio' => null]);	
    	}

    	return response()->json(['save' => true, 'msj' => 'Se registro el año correctamente', 'anio' => $a]);

    }

    public function getPeriodos()
    {

    	$periodos = Periodo::all()->orderBy('cod', ASC);
    	return $periodos->toJson();

    }

    public function getPeriodo($anio)
    {

    	$periodos = Periodo::where('anio', $anio)->orderBy('cod', 'ASC')->get();
    	return $periodos->toJson();
    	
    }

    public function savePeriodo(Request $request)
    {


    	$periodo = explode('/',$request->input('periodo'));
    	$anio = $request->input('anio');

    	$verifPeriodo = Periodo::where('cod', $periodo[0])->where('anio', $anio)->get();

    	if(count($verifPeriodo) > 0){

    		return response()->json(['save' => false, 'msj' => 'No puedes crear el mismo periodo en este año']);
    	}

        $mesPeriodo = substr($periodo[0], 1,3);

        if($anio == date('Y') && $mesPeriodo > date('m')){

            return response()->json(['save' => false, 'msj' => 'No puedes crear periodos posteriores al mes actual']);
        }

    	$anio = AnioFiscal::where('aniofiscal', $anio)->get();

    	$p = new Periodo;
    	$p->cod = $periodo[0];
    	$p->descripcion = $periodo[1];
    	$p->anio_id = $anio[0]->id;
    	$p->anio = $anio[0]->aniofiscal;
    	$p->activo = true;
    	$psave = $p->save();

    	if(!$psave){

    		return response()->json(['save' => false, 'msj' => 'Ocurrio un error al guardar el periodo']);

    	}

    	return response()->json(['save' => true, 'msj' => 'El periodo se creo con exito']);

    }

    public function getCuentasE()
    {
    	$cuentas = Cuenta::with('banco')->where('id','!=','1')->get();

    	return $cuentas->toJson();
    }

    public function getCuentas()
    {
        $cuentas = Cuenta::with('banco')->get();

        return $cuentas->toJson();
    }

    public function saveCuenta(Request $request)
    {
        $data = $request->input('data');

        $verifCuenta = Cuenta::where('numero', $data['numero'])->get();

        if(count($verifCuenta) > 0){
            return response()->json(['save' => false, 'msj' => 'El numero de cuenta ya fue registrado!']);
        }

        $cuenta = new Cuenta;
        $cuenta->banco_id = $data['banco'];
        $cuenta->cedula_titular = $data['cedula'];
        $cuenta->titular = $data['titular'];
        $cuenta->email_titular = $data['email'];
        $cuenta->tipo_cuenta = $data['tipo'];
        $cuenta->numero = $data['numero'];
        $cuenta->capital_inicial = $data['capital'];
        $cuenta->disponible = $data['capital'];
        $csave = $cuenta->save();

        $cbanco = Cuenta::with('banco')->where('id', $cuenta->id)->get();

        if(!$csave){
            return response()->json(['save' => false, 'msj' => 'Ocurrio un error al guardar la cuenta!']);
        }

        return response()->json(['save' => true, 'msj' => 'La cuenta se registró exitosamente!', 'cuenta' => $cbanco[0]]);
    }

    public function deleteCuenta($id)
    {  
        $cuenta = Cuenta::find($id);
        $cdelete = $cuenta->delete();

        if(!$cdelete){

            return response()->json(['delete' => false, 'msj' => 'La cuenta no se puede eliminar']);            
        }

        return response()->json(['delete' => true, 'msj' => 'La cuenta se elimino correctamente']);
    }

    public function getBancos()
    {
        $bancos = Banco::orderBy('descripcion',  'ASC')->get();

        return $bancos->toJson();
    }

    //Tipos de Ingresos

    public function getTipoIngresos()
    {
        $tiposIngresos = TipoIngreso::all();

        return $tiposIngresos->toJson();
    }

    public function updateIngresos($id, Request $request)
    {
        $tipo = TipoIngreso::find($id);
        $tipo->descripcion = $request->input('descripcion');
        $tsave = $tipo->save();

        if(!$tsave){

             return response()->json(['update' => false, 'msj' => 'No se pudo actualizar']);   
        }

        return response()->json(['update' => true, 'msj' => 'Se actualizo correctamente']);  
    }

     public function saveIngresos(Request $request)
    {
        $tipo = new TipoIngreso;
        $tipo->descripcion = $request->input('descripcion');
        $tsave = $tipo->save();

         if(!$tsave){

             return response()->json(['save' => false, 'msj' => 'No se guardo']);   
        }

        return response()->json(['save' => true, 'msj' => 'Se guardo correctamente', 'tipo' => $tipo]);  

        
    }

    //Tipos de egresos
    public function getTipoEgresos()
    {
        $tiposEgresos = TipoEgreso::all();

        return $tiposEgresos->toJson();
    }

    public function updateEgresos($id, Request $request)
    {
        $tipo = TipoEgreso::find($id);
        $tipo->descripcion = $request->input('descripcion');
        $tsave = $tipo->save();

        if(!$tsave){

             return response()->json(['update' => false, 'msj' => 'No se pudo actualizar']);   
        }

        return response()->json(['update' => true, 'msj' => 'Se actualizo correctamente']);  
    }

     public function saveEgresos(Request $request)
    {
        $tipo = new TipoEgreso;
        $tipo->descripcion = $request->input('descripcion');
        $tsave = $tipo->save();

         if(!$tsave){

             return response()->json(['save' => false, 'msj' => 'No se guardo']);   
        }

        return response()->json(['save' => true, 'msj' => 'Se guardo correctamente', 'tipo' => $tipo]);  

        
    }
}
