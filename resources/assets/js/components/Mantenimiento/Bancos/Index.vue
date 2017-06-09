<template>
<div>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-info">
				<div class="box-header with-border">
              		<h3 class="box-title">Cuentas bancarias</h3>
              		<button class="btn btn-xs btn-primary pull-right" @click="showModal = true">Nueva <i class="fa fa-plus"></i></button>
            	</div>
            <div class="box-body">
            	<table class="table table-striped">
            		<thead>
            			<tr>
	            			<th>#</th>
	            			<th>Banco</th>
	            			<th>Cedula Titular</th>
	            			<th>Titukar</td>
	            			<th>Tipo</th>
	            			<th>Numero</th>
	            			<th></th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr v-for="(cuenta, index) in cuentas">
            				<td>{{ index + 1 }}</td>
            				<td>{{ cuenta.banco.descripcion }}</td>
            				<td>{{ cuenta.cedula_titular }}</td>
            				<td>{{ cuenta.titular }}</td>
            				<td v-if="cuenta.tipo == 1">Ahorro</td>
            				<td v-else>Corriente</td>
            				<td>0{{ cuenta.numero }}</td>
            				<td><button rel="tooltip" title="Editar cuenta" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></button>
            				<button rel="tooltip" v-on:click="deleteCuenta(cuenta, index)" title="Eliminar cuenta" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button></td>
            			</tr>
            		</tbody>
            	</table>
			</div>
		</div>
	</div>
</div>
<modal v-if="showModal" @close="showModal = false">
    <h3 slot="header">Registrar una nueva cuenta</h3>
    <div slot="body">
    	<form v-on:submit.prevent="save">
    	<div class="form-group">
    		<select class="form-control" required v-model="nuevo.banco">
    			<option v-for="banco in bancos" :value="banco.id">{{ banco.descripcion }}</option>
    		</select>
    	</div>
    	<div class="form-group">
    		<input type="text" v-model="nuevo.cedula" name="cedula" class="form-control" placeholder="Cedula" required>
    	</div>
    	<div class="form-group">
    		<input type="text" v-model="nuevo.titular" name="titular" class="form-control" placeholder="Titular de la cuneta" required>
    	</div>
    	<div class="form-group">
    		<input type="email" v-model="nuevo.email" name="email" class="form-control" placeholder="Correo electronico" required>
    	</div>
    	<div class="form-group">
    		<select class="form-control" v-model="nuevo.tipo">
    			<option value="1">Ahorros</option>
    			<option value="2">Corrientes</option>
    		</select>
    	</div>
    	<div class="form-group">
    		<input type="text" v-model="nuevo.numero" name="numero" class="form-control" placeholder="Numero de la cuenta" minlength="20" required>
    	</div>
    	<div class="form-group">
    		<input type="text" v-model="nuevo.capital" name="capital" class="form-control" placeholder="Capital inicial" required>
    	</div>
    	<hr>
    	    <button  type="submit" class="btn btn-success pull-right">
       			 Guargar <i class="fa fa-save"></i>
    		</button>
    </form>
    </div>
</modal>
</template>

<script>
import modal from '../../modal.vue';
	export default {
		data () {
			return {
				cuentas: [],
				bancos: [],
				showModal: false,
				nuevo: {banco: 1, cedula: '', titular: '',email: '', tipo: 1, numero: '', capital: '' }
			}
		},
		mounted (){

			this.getCuentas();
			this.getbancos();
		},
		components:{
			modal
		},
		methods: {
			getCuentas: function(){

				this.$http.get('/api/cuentas2').then(response => {

					for (var i = 0; i < response.body.length; i++) {
						this.cuentas.push(response.body[i])	
					}
				}, response => {

				})
			},
			getbancos: function(){

				this.$http.get('/api/bancos').then(response => {

					for (var i = 0; i < response.body.length; i++) {
						this.bancos.push(response.body[i])	
					}
				}, response => {

				})
			},
			save: function(){
				
				this.$http.post('/api/cuentas', {data: this.nuevo }).then(response => {
						var data = response.body;
						if(!data.save){
							this.showModal = false;
							return this.$swal({title: "Error!",
										   html: data.msj,
										   type: 'error'});
						}else{
							this.showModal = false;
							this.$swal({title: "Exito!",
										   html: response.body.msj,
										   type: 'success'});
							this.cuentas.push(data.cuenta);
							this.nuevo = {banco: 1, cedula: '', titular: '',email: '', tipo: 1, numero: '' };
						}



				}, response => {

				})
			},
			deleteCuenta: function(cuenta, index){
				var q = confirm('Deseas eliminar la cuenta 0'+cuenta.numero +'?');

				if(q == true){

					this.$http.delete('/api/cuentas/'+cuenta.id).then(response => {
						var data = response.body;

						this.$swal({title: "Exito!",
										   html: response.body.msj,
										   type: 'success'});
						this.cuentas.splice(index, 1);
					}, response => {
						this.$swal({title: "Error!",
										   html: 'La cuenta no puede ser eliminada!',
										   type: 'error'});
					});
				}
			}
		}
	}
</script>