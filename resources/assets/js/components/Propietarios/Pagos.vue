<template>
<div>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-info">
				<div class="box-header with-border">
              		<h3 class="box-title">Mis pagos</h3>
            	</div>
            <div class="box-body">
            	<p style="font-size: 15px;">Aqui puedes registrar pagos realizados a traves de transferencias y depositos a las distinta cuentas del condomino, asi como ver el estado de los que ha registrado con anterioridad.</p>
            	<br>
            	<button class="btn btn-md btn-success" @click="showModal = true">Registrar nuevo pago <i class="fa fa-money"></button>
            	<hr></hr>
            	<div class="alert alert-info" v-if="pagos.length == 0">
            		<h4>Aun no has registrados pagos!</h4>
            	</div>
            	<table class="table table-striped" v-else>
            		<thead>
            			<tr>
	            			<th>#</th>
	            			<th>Fecha. Op.</th>
	            			<th>Forma</th>
	            			<th>Cuenta</td>
	            			<th>Referencia</th>
	            			<th>Monto</th>
	            			<th>Estatus</th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr v-for="(pago, index) in pagos">
            				<td>{{ index + 1 }}</td>
            				<td>{{ pago.fecha_pago | formatDate('DD/MM/YYYY') }}</td>
            				<td>{{ pago.formapago.descripcion }}</td>
            				<td>0{{ pago.cuenta.numero }}</td>
            				<td>{{ pago.referencia }}</td>
            				<td>{{ pago.monto | currency('') }}</td>
            				<td v-if="pago.confirmado == 0" style="color: #f39c12;"><i class="fa fa-warning"></i> Pendiente</td>
            				<td v-else style="color: #00a65a"><i class="fa fa-check"></i> Aprobado</td>
            			</tr>
            		</tbody>
            	</table>
			</div>
			<div class="box-footer">
				<button class="btn btn-primary btn-xs pull-left"><i class="fa fa-arrow-left"> Antenrior</button>
            	<button class="btn btn-primary btn-xs pull-right">Siguiente <i class="fa fa-arrow-right"></button>	
			</div>
		</div>
	</div>
<modal v-if="showModal" @close="showModal = false">
    <h3 slot="header">Registrar nuevo pago</h3>
    <div slot="body">
        <form>
        	<div class="form-group">
        		<label>Seleccione la forma de pago</label>
        		<select class="form-control" v-model="nuevo.tipoPago" id="formapago">
        			<option value="">--- Seleccione ---</option>
        			<option value="1">Transferencia</option>
        			<option value="2">Deposito</option>
        		</select>
        	</div>
        	<div class="form-group">
        		<label>Seleccion la cuenta donde pagó</label>
        		<select class="form-control" v-model="nuevo.cuenta" id="cuenta">
                <option disabled value="">-- Seleccion la cuenta destino ---</option>
                  <option v-for="cuenta in bancos" :value="cuenta.id">{{cuenta.banco.descripcion}} - 0{{ cuenta.numero }}</option>  
                </select>
        	</div>
        	<div class="form-group">
        		<label>Ingrese la fecha de la operación</label>
        		<input type="text" name="fecha" class="form-control" v-model="nuevo.fecha">
        	</div>
        	<div class="form-group">
        		<label>Referencia o numero de deposito</label>
        		<input type="text" name="deposito" class="form-control" v-model="nuevo.referencia">
        	</div>
        	<div class="form-group">
        		<label>Indique el monto depositado</label>
        		<input type="text" name="monto" class="form-control" v-model="nuevo.monto">
        	</div>
        </form>
    </div>
    <button slot="footer" class="btn btn-success pull-right" @click="preGuardado">Aceptar  <i class="fa fa-check"></i></button>
</modal>
<modal v-if="confirmacion" @close="confirmacion = false">
    <h3 slot="header">Verifica los datos del pago</h3>
    <div slot="body">
        <p>
        	Forma de pago: <b>{{ mensaje.formapago }}</b><br>
        	Cuenta: <b>{{ mensaje.cuenta }}</b><br>
        	Referencia o numero: <b>{{ nuevo.referencia }}</b><br>
        	Monto: <b>{{ nuevo.monto | currency('Bs.') }}</b>
        	<hr>
        	Si los datos son correctos presiona aceptar
        </p>
    </div>
    <button slot="footer" class="btn btn-danger pull-left" @click="confirmacion = false">Cancelar</button>
    <button slot="footer" class="btn btn-success pull-right" v-if="!saving" @click="save">Aceptar  <i class="fa fa-check"></i></button>
    <button slot="footer" class="btn btn-success pull-right" disabled v-else="saving">Registrando...<i class="fa fa-spinner fa-sping"></i></button>
</modal>
</div>
</template>

<script>
 import modal from './modal.vue';
import auth from '../../services/auth';
 import router from '../../routes';
	export default {
		data () {
			return {
				auth: auth,
				pagos: [],
				bancos: [],
				showModal: false,
				confirmacion: false,
				nuevo: null,
				casa: null,
				saving: false,
				nuevo: {tipoPago:'', cuenta: '', fecha: null, referencia: null, monto: null},
				mensaje: {cuenta: '', formaPago: ''}
			}
		},
		mounted (){
			if(!auth.user.authenticated)
      		{
        		return router.push({path: '/'})
      		}

      		this.casa = this.auth.user.profile.casa;

      		this.getPagos()
      		this.getCuentas()
		},
		components:{
			modal
		},
		methods: {
			getPagos: function(){
				this.pagos = [];
				this.$http.get('/api/propietarios/pagos').then(response => {

					for (var i = 0; i < response.body.data.length; i++) {
						this.pagos.push(response.body.data[i])	
					}
				}, response => {

				})
			},
			 getCuentas: function(){

		        this.$http.get('/api/cuentas2').then(response => {

		          for (var i = 0; i < response.body.length; i++) {
		            this.bancos.push(response.body[i]) 
		          }
		        }, response => {

		        })
		      },
		      preGuardado: function(){

		      	this.mensaje.cuenta = $("#cuenta :selected").text()
		      	this.mensaje.formapago = $("#formapago :selected").text()
		      	this.confirmacion = true
		      },
		      save: function(){
		      	this.saving = true
		      	this.$http.post('/api/propietarios/pagos', { data: this.nuevo }).then(response => {

						if(response.body.save){
							this.confirmacion = false
							this.showModal = false
							this.$swal({
								title: 'Exito!',
								text: 'El pago se registro exitosamente, al se verificado se abonara a su saldo y lo vera reflejado en el estado de cuenta!',
								type: 'success'
							})
							this.getPagos();
						}
				}, response => {
					this.confirmacion = false
					this.showModal = false
					this.$swal({
								title: 'Error!',
								text: 'Ocurrio un error al registrar el pago, verifique e intente de nuevo',
								type: 'error'
							})
				})

		      	this.saving = false
		      }
		}
	}
</script>