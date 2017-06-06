<template>
<div>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-info">
				<div class="box-header with-border">
              		<h3 class="box-title">Consolidacion bancaria</h3>
            	</div>
            <div class="box-body">
            	<table class="table table-striped">
            		<thead>
            			<tr>
	            			<th>#</th>
	            			<th>Banco</th>
	            			<th>Tipo</th>
	            			<th>Numero</th>
	            			<th>Disponibilidad</th>
	            			<th></th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr v-for="(cuenta, index) in cuentas">
            				<td>{{ index + 1 }}</td>
            				<td>{{ cuenta.banco.descripcion }}</td>
            				<td v-if="cuenta.tipo == 1">Ahorro</td>
            				<td v-else>Corriente</td>
            				<td>0{{ cuenta.numero }}</td>
            				<td>{{ cuenta.disponible | currency('') }}</td>
            				<td><router-link :to="'/administracion/conban/detalles/'+cuenta.id" rel="tooltip" title="Ver detalles" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></router-link>
            				<button rel="tooltip" v-on:click="deleteCuenta(cuenta, index)" title="Eliminar cuenta" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button></td>
            			</tr>
            		</tbody>
            	</table>
			</div>
		</div>
	 </div>
  </div>
  <router-view></router-view>
</div>
</template>

<script>
import modal from '../../modal.vue';
	export default {
		data () {
			return {
				cuentas: [],
				bancos: []
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
			}
		}
	}
</script>