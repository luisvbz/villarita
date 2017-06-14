<template>
	<div class="row">
		<div class="col col-lg-12">
			<div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Noticias</h3>
              <button class="btn btn-md btn-primary pull-right" @click="showModal = true">Registrar vigilante <i class="fa fa-plus"></i></button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
              	<thead>
              	 <tr>
                  <th style="width: 10%">Cedula</th>
                  <th style="width: 30%">Apellidos</th>
                  <th style="width: 30%">Nombres</th>
                  <th style="width: 10%">Fec. Ing.</th>
                  <th style="width: 5%">Activo?</th>
                  <th style="width: 10%"></th>
                </tr>	
              	</thead>
                <tbody>
                <tr v-for="v in vigilantes">
                  <td>{{ v.cedula }}</td>
                  <td>{{ v.apellidos }}</td>
                  <td>{{ v.nombres }}</td>
                  <td>{{ v.fecing | formatDate('DD/MM/YYYY') }}</td>
                  <td>
                    <button @click="cambiarStatus(v)" v-if="v.activo == 1" class="btn btn-xs btn-success" rel="tooltip" title="Activo (Haga click para desactivar)"><i class="fa fa-check"></i></button>
                    <button @click="cambiarStatus(v)" v-else class="btn btn-xs btn-danger" rel="tooltip" title="Activo (Haga click para activar)"><i class="fa fa-times"></i></button>
                  </td>
                  <td>
                    <button class="btn btn-xs btn-default"><i class="fa fa-edit"></i></button>
                    <button class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button>
                  </td>
                </tr>
              </tbody>
              </table>
            </div>
          </div>
		</div>
    <modal v-if="showModal" @close="showModal = false">
    <h3 slot="header">Registrar nuevo vigilante</h3>
    <div slot="body">
       <div class="form-group">
          <label>Cedula</label>
          <input type="text" class="form-control" name="cedula" v-model="nuevo.cedula">
        </div>
        <div class="form-group">
          <label>Apellidos</label>
          <input type="text" class="form-control" name="apellidos" v-model="nuevo.apellidos">
        </div>
        <div class="form-group">
          <label>Nombres</label>
          <input type="text" class="form-control" name="nombres" v-model="nuevo.nombres">
        </div>
        <div class="form-group">
          <label>Fecha de Ingreso</label>
            <input type="text" class="form-control" name="fecing" v-model="nuevo.fecing">
        </div>
    </div>
    <button  slot="footer" class="btn btn-success" @click="guardarVigilante()">
        Guargar <i class="fa fa-save"></i>
    </button>
</modal>
	</div>
</template>
<script>
  import auth from '../../../services/auth';
  import router from '../../../routes';
  import modal from '../../modal.vue';
  export default {
    data() {
            return {
                auth: auth,
                vigilantes: [],
                showModal: false,
                nuevo: {cedula: null,apellidos: null, nombres: null, fecing: null}
            }
        },
        components:{
          modal
        },
        mounted(){
		      if(!auth.user.authenticated || auth.user.profile.rol == 3)
		      {
		        return router.push({path: '/'})
		      }
                this.getVigilantes()
        },
        methods: {
            getVigilantes: function(){
              this.vigilantes = []
            	this.$http.get('/api/vigilantes').then(response => {
            		for (var i = 0; i < response.body.length; i++) {
            			this.vigilantes.push(response.body[i])
            		};
            	}, response => {

            	})
            },
            guardarVigilante: function(){
              this.$http.post('/api/vigilantes', { data: this.nuevo }).then(response => {

                if(response.body.save){
                  this.showModal = false
                  this.$swal({
                    title: 'Guardado!',
                    text: 'El vigilante se registro con exito',
                    type: 'success'
                  });
                  this.getVigilantes();
                }
              }, response => {
                this.showModal = false
                this.$swal({
                  title: 'Error!',
                  text: 'Ocurrio un error al registrar el vigilante',
                  type: 'error'
                });
              })
            },
            cambiarStatus: function(v){

              this.$http.get('/api/vigilantes/status/'+v.id).then(function(response){
                  v.activo = response.body.activo;
              })
            }
      }
  }
</script>