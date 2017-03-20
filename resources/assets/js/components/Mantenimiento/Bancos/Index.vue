<template>
<div>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-info">
				<div class="box-header with-border">
              		<h3 class="box-title">Cuentas bancarias</h3>
              		<button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#nuevo">Nueva <i class="fa fa-plus"></i></button>
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
<div class="modal fade" id="nuevo" tabindex="-1" role="dialog" aria-labelledby="Nuevo" aria-hidden="true">
	<form v-on:submit.prevent="save">
	<div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">×</span></button>
	        <h4 class="modal-title">Registrar una nueva cuenta</h4>
	      </div>
	      <div class="modal-body">
	        
	        	<div class="form-group">
	        		<label>Entidad bancario</label>
	        		<select class="form-control" required v-model="nuevo.banco">
	        			<option v-for="banco in bancos" :value="banco.id">{{ banco.descripcion }}</option>
	        		</select>
	        	</div>
	        	<div class="form-group">
	        		<label>Cedula del titular</label>
	        		<input type="text" v-model="nuevo.cedula" name="cedula" class="form-control" placeholder="Ingrese la ceduladel titular de la cuenta" required>
	        	</div>
	        	<div class="form-group">
	        		<label>Titular</label>
	        		<input type="text" v-model="nuevo.titular" name="titular" class="form-control" placeholder="Razon social del titular de la cuenta" required>
	        	</div>
	        	<div class="form-group">
	        		<label>Email</label>
	        		<input type="email" v-model="nuevo.email" name="email" class="form-control" placeholder="Razon social del titular de la cuenta" required>
	        	</div>
	        	<div class="form-group">
	        		<label>Tipo</label>
	        		<select class="form-control" v-model="nuevo.tipo">
	        			<option value="1">Ahorros</option>
	        			<option value="2">Corrientes</option>
	        		</select>
	        	</div>
	        	<div class="form-group">
	        		<label>Numero</label>
	        		<input type="text" v-model="nuevo.numero" name="numero" class="form-control" placeholder="Numero de la cuenta" minlength="20" required>
	        	</div>
	        	<div class="form-group">
	        		<label>Capital inicial</label>
	        		<input type="text" v-model="nuevo.capital" name="capital" class="form-control" placeholder="0.00" required>
	        	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
	        <button type="submit" class="btn btn-primary">Guardar <i class="fa fa-save"></i></button>
	      </div>
	    </div>
	  </div>
	  </form>
	</div>
</div>
</template>

<script>
	export default {
		data () {
			return {
				cuentas: [],
				bancos: [],
				nuevo: {banco: 1, cedula: '', titular: '',email: '', tipo: 1, numero: '', capital: 0.00 }
			}
		},
		mounted (){

			this.getCuentas();
			this.getbancos();
		},
		methods: {
			getCuentas: function(){

				this.$http.get('/api/cuentas').then(response => {

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
							return alert(data.msj);
						}else{

							alert(data.msj);
							this.cuentas.push(data.cuenta);
							this.nuevo = {banco: 1, cedula: '', titular: '',email: '', tipo: 1, numero: '' };
							$("#nuevo").modal("hide");
						}



				}, response => {

				})
			},
			deleteCuenta: function(cuenta, index){
				var q = confirm('Deseas eliminar la cuenta 0'+cuenta.numero +'?');

				if(q == true){

					this.$http.delete('/api/cuentas/'+cuenta.id).then(response => {
						var data = response.body;

						alert(data.msj);
						this.cuentas.splice(index, 1);
					});
				}
			}
		}
	}
</script>