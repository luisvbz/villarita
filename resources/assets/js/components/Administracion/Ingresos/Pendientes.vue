<template>
<div>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-info">
				<div class="box-header with-border">
              		<h3 class="box-title">Confirmar pagos recibidos</h3>
              		<h3 class="box-title pull-right">Por confirmar: {{ cuantos }}</h3>
            	</div>
            <div class="box-body">
            	<table class="table table-striped">
            		<thead>
            			<tr>
	            			<th>#</th>
	            			<th>Propietario</th>
	            			<th>Casa</th>
	            			<th>Fecha</th>
	            			<th>Tipo</th>
	            			<th>Cuenta</th>
	            			<th>Referencia</th>
	            			<th>Monto</th>
	            			<th></th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr v-for="(pago, index) in pagos">
            				<td>{{ index + 1 }}</td>
            				<td>{{ pago.user.name }}</td>
            				<td>{{ pago.casa.numero }}</td>
            				<td>{{ pago.fecha_pago | formatDate('DD/MM/YYYY') }}</td>
            				<td>{{ pago.formapago.descripcion }}</td>
            				<td>0{{ pago.cuenta.numero }}</td>
            				<td>{{ pago.referencia }}</td>
            				<td>{{ pago.monto | currency('') }}</td>
            				<td v-if="pago.confirmado == 0">
            					<button class="btn btn-xs btn-success" @click="preGuardado(pago)" rel="toltip" title="confirmar"><i class="fa fa-check"></button>
            					<button class="btn btn-xs btn-danger" rel="toltip" title="rechazar"><i class="fa fa-times"></button>
            				</td>
            				<td v-else style="color: #00a65a"><i class="fa fa-check"></i> Aprobado</td>
            			</tr>
            		</tbody>
            	</table>
			</div>
		</div>
	 </div>
  </div>
  <modal v-if="confirmacion" @close="confirmacion = false">
    <h3 slot="header">Verifica los datos del pago</h3>
    <div slot="body">
        <p>
        	{{ mensaje }}
        </p>
    </div>
    <button slot="footer" class="btn btn-danger pull-left" @click="confirmacion = false">Cancelar</button>
    <button slot="footer" class="btn btn-success pull-right" v-if="!saving" @click="procesarPago(pago)">Aceptar  <i class="fa fa-check"></i></button>
    <button slot="footer" class="btn btn-success pull-right" disabled v-else="saving">Confirmado...<i class="fa fa-spinner fa-sping"></i></button>
</modal>
</div>
</template>

<script>
import modal from '../../modal.vue';
	export default {
		data () {
			return {
				pagos: [],
				cuantos: null,
				confirmacion: false,
				mensaje: null,
				saving: false,
				pago: null
			}
		},
		mounted (){

			this.getPagos();
		},
		components:{
			modal
		},
		methods: {
			getPagos: function(){
				this.pagos = [];
				this.$http.get('/api/ingresos/pagos').then(response => {
					this.cuantos = response.body.cuantos
					for (var i = 0; i < response.body.pagos.data.length; i++) {
						this.pagos.push(response.body.pagos.data[i])	
					}
				}, response => {

				})
			},
			preGuardado: function(pago){

				this.mensaje = 'Confirma que el pago registrado por '+ pago.user.name + ' de la casa # '+ pago.casa.numero + ' fue abonado en la cuenta 0' + pago.cuenta.numero +' a traves de un(a) ' + pago.formapago.descripcion + ' por el monto de: ' + pago.monto;
				this.confirmacion = true;
				this.pago = pago.id
			},
			procesarPago: function(pago){

				this.saving = true;
				this.$http.post('/api/ingresos/pagos', {id: this.pago }).then(response => {

					if(response.body.save){
						this.confirmacion = false
						this.saving = false
						this.$swal({
							title: 'Exito',
							text: 'El pago fue aprobado y procesado',
							type: 'success'
						})
						this.getPagos()
					}

				}, response => {

					this.confirmacion = false
					this.saving = false
						this.$swal({
							title: 'Error',
							text: 'Ocurrio un error',
							type: 'error'
						})
				})

			}
		}
	}
</script>