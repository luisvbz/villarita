<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingreso;
use App\Casa;
use DB;

class IngresosController extends Controller
{
    public function getIngresos()
    {
    	/*$ingresos =  Ingreso::with('casa')
    						->select('casa_id', DB::raw("SUM(deuda) as deuda"), DB::raw("SUM(pago) as pago"))
    						->groupBy('casa_id')
    						->orderBy('casa_id', 'ASC')
    						->get();*/

    	$ingresos = DB::select("SELECT a.id, a.numero, b.id as p_id, b.apellidos, b.nombres, sum(c.deuda) as deuda,sum(c.pago) as pago 
								FROM casas a
								INNER JOIN propietarios b ON b.id = a.propietario_id
								LEFT JOIN ingresos c ON c.casa_id = a.id
								GROUP BY a.id, a.numero, b.id, b.apellidos, b.nombres
								ORDER BY a.id ASC");
    	$data = array();

    	foreach ($ingresos as $i) {

                $saldo = ($i->pago ) - ($i->deuda);     

    		
    		array_push($data, array('casa_id' => $i->id,
    								'numero' => $i->numero,
    						   'propietario_id' => $i->p_id,
    						   'propietario' => $i->apellidos.', '.$i->nombres,
    						   'saldo' => number_format($saldo, 2,',', '.')));
    	}


    	return response()->json($data);
    }

    public function generarCobro(Request $request)
    {
    	$cobro = $request->input('cobro');
        $casas = array();

        $totalCobros = Ingreso::all()->count();

    	foreach ($cobro['casas'] as $key => $value) {

            $verificar = Ingreso::where('codperi', $cobro['codperi'])
                                  ->where('anio', $cobro['anio'])
                                  ->where('casa_id', $value)
                                  ->where('tipo_ingreso_id', $cobro['tipo_ingreso'])
                                  ->get();

            if(count($verificar) > 0){
                $casa = Casa::find($value);

                array_push($casas, array($casa->numero));

            }else{

                $new = new Ingreso;
                $new->casa_id = $value;
                $new->tipo_ingreso_id = $cobro['tipo_ingreso'];
                $new->anio = $cobro['anio'];
                $new->codperi = $cobro['codperi'];
                $new->deuda = $cobro['monto'];
                $new->pago = 0.00;
                $new->save();    
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
}
