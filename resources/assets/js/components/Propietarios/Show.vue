<template>
	<div class="row">
		 <div class="col-md-12">
        <div class="alert alert-danger" v-if="!existe">
                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                <h5>La casa # {{ $route.params.id }} no esta registrada!</h5>
        </div>
          <section class="invoice" v-else>
      <!-- title row -->

      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-home"></i> Casa # {{ casa.numero }}
            <small class="pull-right"></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">  
            <strong>
            <span v-if="propietario.inquilino =! 0">Propietario</span>
            <span v-else>Inquilino</span></strong>
            <br>
            <strong>Cedula: </strong>{{ propietario.cedula }}<br>
            <strong>Apellidos: </strong>{{ propietario.apellidos }}<br>
            <strong>Nombres: </strong>{{ propietario.nombres }}<br>
            <strong>Fech. Nac.: </strong>{{ propietario.fecnac }}<br>
            <strong>Hijos: </strong>{{ hijos.length }}<br>
            <strong>Vehiculos: </strong>{{ vehiculos.length }}<br>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col" v-if="conyuge != null">
            <strong>Conyuge</strong>
            <br>
            <strong>Cedula: </strong>{{ conyuge.cedula }}<br>
            <strong>Apellidos: </strong>{{ conyuge.apellidos }}<br>
            <strong>Nombres: </strong>{{ conyuge.nombres }}<br>
            <strong>Fech. Nac.: </strong>{{ conyuge.fecnac }}<br>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <br>
      <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active" v-if="hijos.length > 0"><a aria-expanded="false" href="#hijos" data-toggle="tab">Hijos</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="hijos" v-if="hijos.length > 0">
                <table class="table table-striped">
                    <thead>
                      <th>#</th>
                      <th>Cedula</th>
                      <th>Apellidos</th>
                      <th>Nombres</th>
                      <th>Fech.Nac</th>
                      <th>Sexo</th>
                      <th>Grado</th>
                    </thead>
                    <tbody>
                      <tr v-for="(hijo, index) in hijos">
                        <td>{{ index + 1 }}</td>
                        <td>{{ hijo.cedula }}</td>
                        <td class="cap">{{ hijo.apellidos }}</td>
                        <td class="cap">{{ hijo.nombre }}</td>
                        <td>{{ hijo.fecnac }}</td>
                        <td>{{ hijo.sexo }}</td>
                        <td>{{ hijo.grado_estudio }}</td>
                      </tr>
                    </tbody>
                </table>
              </div>
            </div>
            <!-- /.tab-content -->
          </div>

      <!-- this row will not appear when printing -->
      <div class="row no-print">
       <!-- <div class="col-xs-12">
          <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
          <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button>
        </div>-->
      </div>
    </section>
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
        existe: true,
        casa: {},
        propietario: {cedula: '', apellidos: '', nombres: '', fecnac: '', inquilino: false},
        conyuge: {},
        hijos: [],
        vehiculos: [],
        auth:auth
      }
    }, 
    mounted(){
      
      if(!auth.user.authenticated)
      {
        return router.push({path: '/'})
      }

      this.getCasa();

    },
    watch: {
      '$route' (to, from){
        if(from.params.id !== to.params.id){
          return this.getCasa();
        }
      }
    },
    methods: {
      getCasa: function(){
        this.hijos = [];
        this.$http.get('/api/casas/'+this.$route.params.id).then(response => {
            var data = response.body[0];
            if(response.body.length == 0){
              this.existe = false;
            }else{
              this.casa = data.casa;
              this.conyuge = data.conyuge;
              for (var i = 0; i < data.hijos.length; i++) {
                this.hijos.push(data.hijos[i]);
              };
              
              this.vehiculos.push(data.vehiculos);

               console.log(data);

              //data propietario
              this.propietario.cedula = data.cedula;
              this.propietario.apellidos = data.apellidos;
              this.propietario.nombres = data.nombres;
              this.propietario.fecnac = data.fecnac;
              this.propietario.inquilino = data.inquilino;
              this.existe = true;
            }

        }, response => {
            //console.log(error);
        })
      }
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

  .cap {
    text-transform: capitalize;
  }
</style>