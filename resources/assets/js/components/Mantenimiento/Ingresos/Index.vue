<template>
<div>
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div class="box box-info">
				<div class="box-header with-border">
              		<h3 class="box-title">Tipos de Ingresos</h3>
              		<button class="btn btn-xs btn-primary pull-right" @click="nuevo">Nuevo <i class="fa fa-plus"></i></button>
            	</div>
            <div class="box-body">
            	<table class="table table-striped">
            		<thead>
            			<tr>
	            			<th>#</th>
	            			<th>Concepto  o Descripcion</th>
	            			<th></th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr v-for="(tipo, index) in tipos">
            				<td>{{ index + 1 }}</td>
            				<td v-if="tipo.editing"><input type="text" v-model="tipo.descripcion" class="form-control" @keyup.esc="tipo.editing = false" :value="tipo.descripcion"></td>
            				<td v-else>{{ tipo.descripcion }}</td>
            				<td>
            				<button v-if="tipo.editing" rel="tooltip" @click="saveOrUpdate(tipo)" title="Guardar Cambios" class="btn btn-sm btn-primary"><i class="fa fa-save"></i></button>
            				<button v-if="!tipo.editing || tipo.id != 0" rel="tooltip" @click="editar(tipo)" title="Editar tipo de ingreso" class="btn btn-sm btn-success"><i class="fa fa-edit"></i></button>
            				<button v-if="tipo.editing && tipo.id != 0" rel="tooltip" @click="tipo.editing = false" title="Cancelar edicion" class="btn btn-sm btn-warning"><i class="fa fa-times"></i></button>
            				<button v-if="tipo.id != 0" @click="deleteTipo(tipo, index)" rel="tooltip" title="Eliminar cuenta" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i></button>
            				<button v-if="tipo.id == 0" rel="tooltip" @click="tipos.splice(index, 1)" title="quitar" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
            				</td>
            			</tr>
            		</tbody>
            	</table>
			</div>
		</div>
	</div>
</div>
</template>

<script>
import Vue from 'vue';
	export default {
		data () {
			return {
				tipos: []
			}
		},
		mounted (){

			this.getTiposIngresos();
		},
		methods: {
			getTiposIngresos: function(){

				this.$http.get('/api/tipoingresos').then(response => {
					for (var i = 0; i < response.body.length; i++) {
						this.tipos.push(response.body[i])	
					}
				}, response => {

				})
			},
			editar: function(tipo)
			{
				Vue.set(tipo, 'editing', true);
			},
			nuevo: function(){

				this.tipos.push({id: '0', descripcion: '', editing: true ,created_at: '', updated_at: ''})
			},
			saveOrUpdate: function(tipo){

				if(tipo.id == 0){

					this.$http.post('/api/tipoingresos', {descripcion: tipo.descripcion}).then(response => {

						if(response.body.save){
							tipo.id = response.body.tipo.id;
							tipo.editing = false;
						}

						alert(response.body.msj);

					})

				}else{

					this.$http.put('/api/tipoingresos/'+tipo.id, {id: tipo.id, descripcion: tipo.descripcion}).then(response => {
								alert(response.body.msj);
								tipo.editing = false;
					})
				}
			},
			deleteTipo: function(tipo, index){

				var q = confirm('Deseas borrar este concepto de ingreso ('+tipo.descripcion+')?');

				this.tipos.splice(index, 1);
			} 
		}
	}
</script>