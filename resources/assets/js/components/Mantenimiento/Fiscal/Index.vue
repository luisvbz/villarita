<template>
<div>
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="box box-info">
				<div class="box-header with-border">
              		<h3 class="box-title">Apertura y cierres de año fiscal</h3>
              		<button class="btn btn-xs btn-primary pull-right" @click="nuevo = true">Nuevo <i class="fa fa-plus"></i></button>
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
<modal v-if="nuevo" @close="nuevo = false">
    <h3 slot="header">Registrar nuevo año fiscal</h3>
    <div slot="body">
    	<div class="form-group">
	        		<label>Año</label>
	        		<select class="form-control" required v-model="anio">
	        			<option value="2015">2015</option>
	        			<option value="2016">2016</option>
	        			<option value="2017">2017</option>
	        			<option value="2018">2018</option>
	        			<option value="2019">2019</option>
	        			<option value="2021">2021</option>
	        			<option value="2022">2022</option>
	        			<option value="2022">2022</option>
	        			<option value="2022">2022</option>
	        			<option value="2022">2022</option>
	        			<option value="2022">2022</option>
	        		</select>
	        	</div>
    </div>
    <button  slot="footer" class="btn btn-success" @click="save()">
        Guargar <i class="fa fa-save"></i>
    </button>
</modal>
</template>

<script>
import modal from '../../modal.vue';
	export default {
		data () {
			return {
				anios: [],
				anio: '2016',
				descripcion: '',
				nuevo: false
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
				
				this.$http.post('/api/aniofiscal', {anio: this.anio }).then(response => {
						var data = response.body;
						if(!data.save){
							this.nuevo = false;
							return this.$swal({title: "Error!", text: data.msj, type: 'error'});
						}else{
							this.$swal({title: "Exito!", text: data.msj, type: 'success'});
							this.anios.push(data.anio);
						}

						this.nuevo = false;

				}, response => {

				})
			}
		},
		components:{
			modal
		}
	}
</script>