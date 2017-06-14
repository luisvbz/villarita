<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style>
.tg  {border-collapse:collapse;border-spacing:0; width:100%;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:5px 5px;border-style:solid;border-width:0.5px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-wr85{font-weight:bold;background-color:#efefef;text-align:center}
.tg .tg-yeb6{font-weight:bold;background-color:#efefef;text-align:right}
.tg .tg-ipa1{font-weight:bold;background-color:#c0c0c0;text-align:center}
.red {color: #f56954;},
.green {color: #00a65a;}
.titulo{
	position: fixed;
	top: 15;
	text-align: center;
	font-family:Arial, sans-serif;font-size:16px;
}
.numero{
	position: fixed;
	top: 13;
	text-align: right;
	font-family:Arial, sans-serif;
	font-size:20px;
}
</style>
</head>
<body>
<h1 class="titulo">CONDOMINIO VILLA RITA</h1>
<span class="numero">Casa <b>#{!! $numero !!}</b></span>
<img src="img/logo_small.png" width="190" height="74">
<br>
<table class="tg">
			  <tr>
			    <th class="tg-ipa1" colspan="10">ESTADO DE CUENTA</th>
			  </tr>
			  <tr>
			    <td class="tg-yeb6">Cedula</td>
			    <td class="tg-031e">{!! number_format($propietario['cedula'], 0, ",", ".") !!}</td>
			    <td class="tg-yeb6">Propietario</td>
			    <td class="tg-031e" colspan="5">{!! $propietario['apellidos'].', '.$propietario['nombres'] !!}</td>
			    <td class="tg-yeb6">Casa</td>
			    <td class="tg-031e">{!! $numero !!}</td>
			  </tr>
			  <tr>
			    <td class="tg-ipa1" colspan="10">DETALLES DE CUENTA</td>
			  </tr>
			  <tr>
			    <td class="tg-wr85">Nº</td>
			    <td class="tg-wr85" style="width: 15%;">FECHA</td>
			    <td class="tg-wr85" colspan="4">DETALLES DEL MOVIMIENTO</td>
			    <td class="tg-wr85" colspan="2" style="width: 15%;">DEBITOS</td>
			    <td class="tg-wr85" colspan="2" style="width: 15%;">CREDITOS</td>
			  </tr>
			  <?php $i = 1;
			  $totalMonto = 0;
			  $totalDebitos = 0;
			  $totalCreditos = 0;
			  ?>
			  @foreach($estCuenta as $c)
			  <tr>
			    <td class="tg-031e" style="width: 3%; text-align: center;">{!! $i++ !!}</td>
			    <td class="tg-031e" style="text-align:center;">{!! $c->created_at->format('d/m/Y') !!}</td>
			    @if($c->tipo == 'D')
			    <td class="tg-031e" colspan="4">{!! $c->tipoIngreso->descripcion !!} / {!! $c->periodo->descripcion !!} {!! $c->anio !!}</td>
			    @else
			  		@if($c->forma_pago_id == 1)
			  			<td class="tg-031e" colspan="4">Transferencia: {!! $c->referencia!!}/</td>
			  		@elseif($c->forma_pago_id == 2)
			  			<td class="tg-031e" colspan="4">Deposito: {!! $c->referencia!!}/</td>
			  		@else
			  			<td class="tg-031e" colspan="4">Pago recibido en efectivo</td>
			  		@endif  
			    @endif
			  	<td colspan="2" class="tg-031e" style="text-align: right;"><span style="color: red;">
			  		@if($c->tipo == 'D')
			  			{!! number_format($c->monto, 2, ",", ".")!!}</span>
			  		@endif
			  	</td>
			  	<td colspan="2" class="tg-031e" style="text-align: right;">
			  		@if($c->tipo == 'C')
			  			{!! number_format($c->monto, 2, ",", ".")!!}</span>
			  		@endif
			  	</td>
			  </tr>
			  <?php $totalMonto = $totalMonto + $c->monto; 
			  	if($c->tipo == 'D'){
			  		$totalDebitos = $totalDebitos + $c->monto;
			  	}else{
			  		$totalCreditos = $totalCreditos + $c->monto;
			  	}
			  ?>
			  @endforeach
		<tr>
			<td class="tg-031e" style="text-align:right;" colspan="6"><b>Subtotal</b></td>	
			<td class="tg-031e" colspan="2" style="text-align:right;">
				{!! number_format($totalDebitos, 2, ",", ".") !!}
			</td>
			<td class="tg-031e" colspan="2" style="text-align:right;">
				{!! number_format($totalCreditos, 2, ",", ".") !!}
			</td>
		</tr>
        <tr>
          <td class="tg-031e" style="text-align:right;" colspan="6"><b>Total</b></td>
          <td class="tg-031e" colspan="4" style="text-align:right;"><span><b>{!! number_format($totalMonto, 2, ",", ".") !!}</b></span></td>
        </tr>
		</table>
</body>
</html>