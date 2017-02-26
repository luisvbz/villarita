<template>
	<div class="row">
		 <div class="col-md-12">
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-primary">
              <div class="widget-user-image">
              	<div class="numero">{{ casa.numero }}</div>
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username">{{ propietario.apellidos }}, {{ propietario.nombres }} </h3>
              <h5 class="widget-user-desc">C.I: {{ propietario.cedula }}</h5>
            </div>
            <div class="box-body">
            		{{ hijos }}
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                <li><a href="#">Projects <span class="pull-right badge bg-blue">31</span></a></li>
                <li><a href="#">Tasks <span class="pull-right badge bg-aqua">5</span></a></li>
                <li><a href="#">Completed Projects <span class="pull-right badge bg-green">12</span></a></li>
                <li><a href="#">Followers <span class="pull-right badge bg-red">842</span></a></li>
              </ul>
            </div>
          </div>
		</div>
	</div>
</template>
<script>
 import router from '../../routes';
 import auth from '../../services/auth';
 import Ruta from '../Ruta.vue';
  export default {
    data () {
      return {
        casa: {},
        propietario: {cedula: '', apellidos: '', nombres: ''},
        conyuge: {},
        hijos: [],
        auth:auth
      }
    }, 
    mounted(){
      
      if(!auth.user.authenticated)
      {
        return router.push({path: '/'})
      }
      this.loading = true;
       this.$http.get('/api/casas/'+this.$route.params.id).then(response => {
       	 		var data = response.body[0];
       			this.casa = data.casa;
       			this.conyuge = data.conyuge;
       			this.hijos.push(data.hijos);

       			//data propietario
       			this.propietario.cedula = data.cedula;
       			this.propietario.apellidos = data.apellidos;
       			this.propietario.nombres = data.nombres;

        }, response => {
            //console.log(error);
        })
    },
    components: {
      Ruta
    }
  }
</script>
<style type="text/css">
	.numero{
		background: white;
		text-align: center;
		vertical-align: bottom;
		color: #0073b7;
		font-size: 40px;
		width: 65px;
  		height: auto;
  		float: left;
  		border-radius: 50%;
	}
</style>