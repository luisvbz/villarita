<template>
<div>
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="box box-info">
				<div class="box-header with-border">
              		<h3 class="box-title">Apertura y cierres de año fiscal</h3>
              		<button class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#nuevo">Nuevo <i class="fa fa-plus"></i></button>
            	</div>
            <div class="box-body">
            	<table class="table table-striped">
            		<thead>
            			<tr>
	            			<th>#</th>
	            			<th>Año</td>
	            			<th>Descripcion</th>
	            			<th>Estado</th>
            			</tr>
            		</thead>
            		<tbody>
            			<tr v-for="(anio, index) in anios">
            				<td>{{ index + 1 }}</td>
            				<td>{{ anio.aniofiscal }}</td>
            				<td>{{ anio.descripcion }}</td>
            				<td><i v-if="anio.activo" class="fa fa-check"></i></td>
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
	        <h4 class="modal-title">Registrar nuevo año fiscal</h4>
	      </div>
	      <div class="modal-body">
	        
	        	<div class="form-group">
	        		<label>Año</label>
	        		<select class="form-control" required v-model="anio">
	        			<option value="2015">2015</option>
	        			<option value="2016">2016</option>
	        			<option value="2017">2017</option>
	        			<option value="2018">2018</option>
	        			<option value="2019">2019</option>
	        			<option value="2020">2020</option>
	        		</select>
	        	</div>
	        	<div class="form-group">
	        		<label>Descripcion</label>
	        		<input type="text" v-model="descripcion" name="descripcion" class="form-control" placeholder="Ingrese una descripcion" required>
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
				anios: [],
				anio: '2015',
				descripcion: ''
			}
		},
		mounted (){

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
			save: function(){
				
				this.$http.post('/api/aniofiscal', {anio: this.anio, desc: this.descripcion }).then(response => {
						var data = response.body;
						if(!data.save){
							return alert(data.msj);
						}else{

							alert(data.msj);
							this.anios.push(data.anio);
						}



				}, response => {

				})
			}
		}
	}
</script>