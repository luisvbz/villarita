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
              		<button class="btn btn-xs btn-primary pull-right" @click="showModal= true">Nuevo <i class="fa fa-plus"></i></button>
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
<modal v-if="showModal" @close="showModal = false">
    <h3 slot="header">Registrar nuevo periodo</h3>
    <div slot="body">
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
				periodos: [],
				anios: [],
				anio: '2017',
				loading: false,
				nuevo: {mes: '001/Enero', anio: '2017'},
				showModal: false
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
						this.$swal('No hay periodos para el año ', 'error');
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
						this.showModal = false;
						return this.$swal({title: "Error!",
										   html: response.body.msj,
										   type: 'error'});
						this.showModal = true;

					}else{
						this.showModal = false;
						this.$swal({title: "Exito!",
										   html: response.body.msj,
										   type: 'success'});
						this.getPeriodos(this.anio);

					}
				}, response =>{

				})			
			}
		},
		components:{
			modal
		}
	}
</script>