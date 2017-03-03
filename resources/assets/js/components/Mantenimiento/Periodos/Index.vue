<template>
<div>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-info">
				<div class="box-header with-border">
              		
              		<div class="col-sm-2">
              			<select class="form-control col-sm-2" v-model="anio">
              			  <option v-for="anio in anios" :value="anio.aniofiscal">{{ anio.aniofiscal }}</option>
              			</select>
              		</div>
              		<div class="col-sm-2">
              			<button class="btn btn-primary" v-on:click="getPeriodos(anio)"><i class="fa fa-filter"></button>
              		</div>
              		<button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#nuevo">Nuevo <i class="fa fa-plus"></i></button>
            	</div>
            <div class="box-body">
            	<table class="table table-striped">
            		<thead>
            			<tr>
	            			<th>#</th>
	            			<th>Cod.</th>
	            			<th>Descripcion</th>
	            			<th>Año Fiscal</th>
	            			<th>Estado</th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr v-for="(periodo, index) in periodos">
            				<td>{{ index + 1 }}</td>
            				<td>{{ periodo.cod }}</td>
            				<td>{{ periodo.descripcion }}</td>
            				<td>{{ periodo.anio }}</td>
            				<td><i v-if="periodo.activo" class="fa fa-check"></i></td>
            			</tr>
            		</tbody>
            	</table>
			</div>
			<div class="overlay" v-if="loading">
              <i class="fa fa-refresh fa-spin"></i>
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
	        <h4 class="modal-title">Registrar nuevo periodo</h4>
	      </div>
	      <div class="modal-body">
	    	<div class="form-group">
	    		<label>Mes</label>
	    		<select class="form-control" v-model="nuevo.mes">
	    			<option value="001/Enero">Enero</option>
	    			<option value="002/Febrero">Febrero</option>
	    			<option value="003/Marzo">Marzo</option>
	    			<option value="004/Abril">Abril</option>
	    			<option value="005/Mayo">Mayo</option>
	    			<option value="006/Junio">Junio</option>
	    			<option value="007/Julio">Julio</option>
	    			<option value="008/Agosto">Agosto</option>
	    			<option value="009/Septiempre">Septiempre</option>
	    			<option value="010/Octubre">Octubre</option>
	    			<option value="011/Noviembre">Noviembre</option>
	    			<option value="012/Diciembre">Diciembre</option>
	    		</select>
	    	</div>
	    	<div class="form-group">
	    		<label>Año fiscal</label>
	    		<select class="form-control col-sm-2" v-model="nuevo.anio">
              		<option v-for="anio in anios" :value="anio.aniofiscal">{{ anio.aniofiscal }}</option>
              	</select>
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
				periodos: [],
				anios: [],
				anio: '2017',
				loading: false,
				nuevo: {mes: '001/Enero', anio: '2017'}
			}
		},
		mounted (){

			this.getPeriodos(this.anio);
			this.getAnios();
		},
		methods: {
			getAnios: function(){

				this.$http.get('/api/aniofiscal').then(response => {
					for (var i = 0; i < response.body.length; i++) {
						this.anios.push(response.body[i])	
					}
				}, response => {

				})
			},
			getPeriodos: function(anio){
				this.loading = true;
				this.$http.get('/api/periodos/'+anio).then(response => {
					this.periodos = [];
					if(response.body.length == 0){
						alert('No hay periodos para el año '+ anio);
					}
					for (var i = 0; i < response.body.length; i++) {
						this.periodos.push(response.body[i])	
					}
					this.loading = false;
				}, response => {

				})
			},
			save: function(){

				this.$http.post('/api/periodos',{periodo: this.nuevo.mes, anio: this.nuevo.anio}).then(response =>{
					if(!response.body.save){

						return alert(response.body.msj);

					}else{

						alert(response.body.msj);
						this.getPeriodos(this.anio);

					}
				}, response =>{

				})			
			}
		}
	}
</script>