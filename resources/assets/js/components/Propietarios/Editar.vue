<template>
	<div class="row">
	<div class="col-lg-12">
		<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Agregar nuevo ocupante o inquilino</h3>
            </div>
            <form class="form-horizontal" v-on:submit.prevent="savePropietario">
              <div class="box-body">
                <div class="form-group">
                  <label for="calle" class="col-sm-2 control-label">Calle #</label>

                  <div class="col-sm-1">
                    <select class="form-control" id="calle" v-model="casa.calle" required>
                    	<option value="1">1</option>
                    	<option value="2">2</option>
                    	<option value="3">3</option>
                    	<option value="4">4</option>
                    	<option value="5">5</option>
                    	<option value="6">6</option>
                    	<option value="7">7</option>
                    </select>
                  </div>
                  <label for="casa" class="col-sm-2 control-label">Casa #</label>
                  <div class="col-sm-2">
                  	<input type="number" class="form-control" maxlength="3" v-model="casa.numero" required>
                  </div>
                  <div class="col-sm-4">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" v-model="propietario.inquilino" :value="propietario.inquilino"> Inquilino
                      </label>
                     </div>
                   </div>
                </div>
                <div class="form-group">
                  <label for="cedula" class="col-sm-2 control-label">Cedula de Identidad</label>

                  <div class="col-sm-4">
                    <input class="form-control" id="cedula" placeholder="Ej: 1234567" type="text" v-model="propietario.cedula" required>
                  </div>
                </div>
                <div class="form-group">
                	<label for="apellidosp" class="col-sm-2 control-label">Apellidos</label>
                	<div class="col-sm-10">
                    	<input class="form-control" id="apelldosp" placeholder="Ingrese los apellidos del propietario u oupante" type="text" v-model="propietario.apellidos" required>
                  </div>
                </div>
                <div class="form-group">
                	<label for="nombresp" class="col-sm-2 control-label">Nombres</label>
                	<div class="col-sm-10">
                    	<input class="form-control" id="nombresp" placeholder="Ingrese los nombres del propietario u oupante" type="text" v-model="propietario.nombres" required> 
                  </div>
                </div>
                <div class="form-group">
                	<label for="fecnacp" class="col-sm-2 control-label">Fec. Nac.</label>
                	<div class="col-sm-2">
                    	<input class="form-control" id="fecnacp" placeholder="00/00/0000" type="text" v-model="propietario.fecnac" required>
                  </div>
                  <label for="sexop" class="col-sm-2 control-label">Sexo</label>
	                	<div class="col-sm-2">
	                    	<select class="form-control" v-model="propietario.sexo" required>
	                    		<option value="F">F</option>
	                    		<option value="M">M</option>
	                    	</select>
	                  </div>
                </div>
                <div class="form-group">
                	<label for="profesionp" class="col-sm-2 control-label">Profesion u Oficio</label>
                	<div class="col-sm-10">
                    	<input class="form-control" id="profesionp" placeholder="Ingrese la profesion u oficio del ocupante" type="text" v-model="propietario.profesion" required>
                  </div>
                </div>
                <div class="form-group">
                	<label for="empresap" class="col-sm-2 control-label">Empresa donde trabaja</label>
                	<div class="col-sm-10">
                    	<input class="form-control" id="empresap" placeholder="Ingrese la empresa doonde trabaja el ocupante o inquilino" type="text" v-model="propietario.empresa" required>
                  </div>
                </div>
                <div class="form-group">
                	<label class="col-sm-2 control-label">Telefonos</label>
                	<div class="col-sm-2">
                    	<input class="form-control" id="tel1" placeholder="" type="text" v-model="propietario.tel1" required>
                  </div>
                  <div class="col-sm-2">
                    	<input class="form-control" id="tel2" placeholder="" type="text" v-model="propietario.tel2">
                  </div>
                  <div class="col-sm-2">
                    	<input class="form-control" id="tel3" placeholder="" type="text" v-model="propietario.tel3">
                  </div>
                </div>
                <div class="form-group">
                	<label for="profesionp" class="col-sm-2 control-label">E-mail</label>
                	<div class="col-sm-10">
                    	<input class="form-control" type="email" v-model="propietario.email" id="profesionp" placeholder="ejemplo@gmail.com" required>
                  </div>
                </div>
                <div class="form-group">
                	<div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" v-model="hasConyuge" :value="hasConyuge"> Marque esta casilla si el propietario o inquilino posee Conyuge
                      </label>
                    </div>
                  </div>
                </div>
               	<!-- Datos del conyuge -->
                <div v-if="hasConyuge">
                	 <div class="form-group">
	                  <label for="cedulaC" class="col-sm-2 control-label">Cedula de Identidad</label>

	                  <div class="col-sm-4">
	                    <input class="form-control" id="cedulaC" placeholder="Ej: 1234567" type="text" v-model="conyuge.cedula" required>
	                  </div>
	                </div>
	                <div class="form-group">
	                	<label for="apellidosc" class="col-sm-2 control-label">Apellidos</label>
	                	<div class="col-sm-10">
	                    	<input class="form-control" id="apelldosc" placeholder="Ingrese los apellidos del conyuge" type="text" v-model="conyuge.apellidos" required>
	                  </div>
	                </div>
	                <div class="form-group">
	                	<label for="nombresp" class="col-sm-2 control-label">Nombres</label>
	                	<div class="col-sm-10">
	                    	<input class="form-control" id="nombresc" placeholder="Ingrese los nombres del conyuge" type="text" v-model="conyuge.nombres" required>
	                  </div>
	                </div>
	                <div class="form-group">
	                	<label for="fecnacc" class="col-sm-2 control-label">Fec. Nac.</label>
	                	<div class="col-sm-2">
	                    	<input class="form-control" id="fecnacc" placeholder="00/00/0000" type="text" v-model="conyuge.fecnac" required> 
	                  </div>
	                  <label for="sexoc" class="col-sm-2 control-label">Sexo</label>
	                	<div class="col-sm-2">
	                    	<select class="form-control" v-model="conyuge.sexo" required>
	                    		<option value="F">F</option>
	                    		<option value="M">M</option>
	                    	</select>
	                  </div>
	                </div>
	                <div class="form-group">
	                	<label for="profesionp" class="col-sm-2 control-label">Profesion u Oficio</label>
	                	<div class="col-sm-10">
	                    	<input class="form-control" id="profesionc" placeholder="Ingrese la profesion u oficio del conyuge" type="text" v-model="conyuge.profesion" required>
	                  </div>
	                </div>
	                <div class="form-group">
	                	<label for="empresap" class="col-sm-2 control-label">Empresa donde trabaja</label>
	                	<div class="col-sm-10">
	                    	<input class="form-control" id="empresac" placeholder="Ingrese la empresa donde trabaja el conyuge" type="text" v-model="conyuge.empresa" required>
	                  </div>
	                </div>
	                <div class="form-group">
	                	<label class="col-sm-2 control-label">Telefonos</label>
	                	<div class="col-sm-2">
	                    	<input class="form-control" id="telc1" placeholder="0414123467" type="text" v-model="conyuge.telefono1" required>
	                  </div>
	                  <div class="col-sm-2">
	                    	<input class="form-control" id="telc3" placeholder="0414123467" type="text" v-model="conyuge.telefono2">
	                  </div>
	                  <div class="col-sm-2">
	                    	<input class="form-control" id="telc3" placeholder="0414123467" type="text" v-model="conyuge.telefono3">
	                  </div>
	                </div>
                </div>
                <!--/Datos conyuge -->
                <div class="form-group">
                	<div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" v-model="hasHijos" :value="hasHijos"> Marque esta casilla si el propietario o inquilino posee hijos
                      </label>&nbsp;
                      <button v-if="hasHijos" 
                       		   :disabled="hijos.length >= 6"
                      		  @click.prevent="addHijo" 
                      		  class="btn btn-xs btn-primary">
                      		  <i class="fa fa-plus"></i>
                      </button>&nbsp;
                      <button v-if="hasHijos"
                      		  :disabled="hijos.length == 1" 
                      		  @click.prevent="removeHijo" 
                      		  class="btn btn-xs btn-danger">
                      		  <i class="fa fa-minus"></i>
                      </button>
                    </div>
                  </div>
                </div>
                 <!--Datos hijos -->
                <div v-if="hasHijos">
                	 <div class="form-group" v-for="(hijo, index) in hijos">
	                	<label class="col-sm-2 control-label">Hijo #{{ index + 1}}</label>
	                	<div class="col-sm-2">
	                    	<input class="form-control" v-model="hijo.cedula" placeholder="Cedula" type="text">
	                  </div>
	                	<div class="col-sm-2">
	                    	<input class="form-control" v-model="hijo.apellidos" placeholder="Apelldos" type="text" required>
	                  </div>
	                  <div class="col-sm-2">
	                    	<input class="form-control" v-model="hijo.nombre"  placeholder="Nombres" type="text" required>
	                  </div>
	                   <div class="col-sm-2">
	                    	<input class="form-control" v-model="hijo.fecnac" placeholder="01/01/2001" type="text" required>
	                  </div>
	                  <div class="col-sm-1">
	                    	<select class="form-control" v-model="hijo.sexo" required>
	                    		<option value="F">F</option>
	                    		<option value="M">M</option>
	                    	</select>
	                  </div>
	                   <div class="col-sm-1">
	                    	<select class="form-control" v-model="hijo.grado_estudio" required>
	                    		<option value="F">Pre-Escolar</option>
	                    		<option value="M">Basica</option>
	                    		<option value="M">Bachillerato</option>
	                    		<option value="M">Universitario</option>
	                    	</select>
	                  </div>
	                </div>
                </div>
                <!--/Datos hijos -->
                <div class="form-group">
                	<div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" v-model="hasVehiculos" :value="hasVehiculos"> Marque esta casilla si el propietario o inquilino posee veniculo(s)
                      </label>&nbsp;
                      <button v-if="hasVehiculos" 
                       		   :disabled="vehiculos.length >= 6"
                      		  @click.prevent="addVehiculo" 
                      		  class="btn btn-xs btn-primary">
                      		  <i class="fa fa-plus"></i>
                      </button>&nbsp;
                      <button v-if="hasVehiculos"
                      		  :disabled="vehiculos.length == 1" 
                      		  @click.prevent="removeVehiculo" 
                      		  class="btn btn-xs btn-danger">
                      		  <i class="fa fa-minus"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div v-if="hasVehiculos">
                	<div class="form-group" v-for="(v, index) in vehiculos">
	                	<label class="col-sm-2 control-label">Vehiculo #{{ index + 1}}</label>
	                	<div class="col-sm-2">
	                		<select class="form-control" v-model="v.marca" required>
	                			<option value="Chevrolet">Chevrolet</option>		
	                			<option value="Ford">Ford</option>		
	                			<option value="Chyrsler">Chyrsler</option>		
	                		</select>
	                  	</div>
	                	<div class="col-sm-2">
	                    	<input class="form-control"  v-model="v.modelo" placeholder="Modelo" type="text" required>
	                  	</div>
	                  	<div class="col-sm-2">
	                    	<input class="form-control"  v-model="v.anio" placeholder="Año" type="text" required>
	                  	</div>
	                  	<div class="col-sm-2">
	                    	<input class="form-control"  v-model="v.placa" placeholder="Placa" type="text" required>
	                  	</div>
	                 </div>
                </div>
              </div>	
              <div class="box-footer">
                <router-link to="/propietarios" class="btn btn-default"><i class="fa fa-arrow-left"> Regresar</router-link>
                <button type="submit" class="btn btn-primary pull-right">Actualizar <i class="fa fa-refresh"></i></button>
              </div>
            </form>
            <div class="overlay" v-if="saving">
              <i class="fa fa-spinner fa-spin"></i>
           </div>
          </div>
	</div>
		
	</div>
</template>
<script>
import router from '../../routes';
import auth from '../../services/auth';

export default {
	data () {
		return {
			hasConyuge: false,
			hasHijos: false,
			hasVehiculos: false,
			saving: false,
			casa: {},
			propietario: {id: '', inquilino: false, cedula: '', apellidos: '', nombres: '',
						  fecnac: '', sexo: '', profesion: '', empresa: '', tel1: '', tel2: '', tel3: '', email: ''},
			conyuge: {},
			hijos: [],
			vehiculos: []
		}
	},
	 mounted(){
      
      if(!auth.user.authenticated)
      {
        return router.push({path: '/'})
      }

      this.getCasa();

    },
	methods: {
		getCasa: function(){
        this.hijos = [];
        this.vehiculos = [];
        this.$http.get('/api/casas/'+this.$route.params.id).then(response => {
            var data = response.body.propietario[0];
            if(response.body.propietario.length == 0){
              this.existe = false;
            }else{
              this.casa = data.casa;

              if(data.conyuge != null){
              	this.hasConyuge = true;
              	this.conyuge = data.conyuge;
              }

              if(data.hijos.length > 0){
              	this.hasHijos = true;
              	for (var i = 0; i < data.hijos.length; i++) {
                this.hijos.push(data.hijos[i]);
               }
              }

              if(data.vehiculos.length > 0){
              	this.hasVehiculos = true;
              	for (var i = 0; i < data.vehiculos.length; i++) {
                this.vehiculos.push(data.vehiculos[i]);
               }
              }
             
              //data propietario
              this.propietario.id = data.id;
              this.propietario.inquilino = data.inquilino;
              this.propietario.cedula = data.cedula;
              this.propietario.apellidos = data.apellidos;
              this.propietario.nombres = data.nombres;
              this.propietario.fecnac = data.fecnac;
              this.propietario.sexo = data.sexo;
              this.propietario.profesion = data.profesion;
              this.propietario.empresa = data.empresa;
              this.propietario.tel1 = data.telefono1;
              this.propietario.tel2 = data.telefono2;
              this.propietario.tel3 = data.telefono3;
              this.propietario.email = data.email;
            }

	        }, response => {
	            //console.log(error);
	        })
    	},
		addHijo: function(){

			this.hijos.push({cedula: '', apellidos: '', nombres: '', fecnac: '', sexo: '', grado: ''});
		},
		removeHijo: function(){
			var index = (this.hijos.length);
			var last = index - 1;
			console.log(last);
			this.hijos.splice(last,1);
		},
		addVehiculo: function(){

			this.vehiculos.push({marca: '', modelo: '', anio: '', placa: ''});
		},
		removeVehiculo: function(){
			var index = (this.vehiculos.length);
			var last = index - 1;
			console.log(last);
			this.vehiculos.splice(last,1);
		},
		savePropietario: function(){
			this.saving = true;
			this.$http.post('/api/casas/edit/'+this.$route.params.id, {hasConyuge: this.hasConyuge, 
									   hasHijos: this.hasHijos,
									   hasVehiculos: this.hasVehiculos,
									   propietario: this.propietario, 
									   conyuge: this.conyuge, 
									   hijos: this.hijos,
									   vehiculos: this.vehiculos,
										casa: this.casa}).then(response => {
									   		console.log(response);
									   		if(!response.body.save){
                          this.$swal({
                            title: 'Ocurrio un error!',
                            text: response.body.msj,
                            type: 'error'
                          })
									   			alert(response.body.msj);
									   		}else{
									   			this.$swal({
                            title: 'Listo!',
                            text: response.body.msj,
                            type: 'success'
                          })
                          	this.saving = false;
                            router.push({path: '/propietarios'});  
                        }
									   		
									   }, response => {
					        //console.log(error);
					    	})
		}
	}
}
</script>