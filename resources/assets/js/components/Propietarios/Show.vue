<template>
	<div class="row">
		 <div class="col-md-12">
        <div class="alert alert-danger" v-if="!existe">
                <h4><i class="fa fa-ban"></i> Error!</h4>
                <h5>La casa # {{ $route.params.id }} no esta registrada!</h5>
        </div>
          <section class="invoice" v-else>
      <!-- title row -->

      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-home"></i> Casa # {{ casa.numero }}
            <span class="pull-right">
             Bs. <span :style="saldo < 0 ? 'color: #f56954;' : 'color: #00a65a;'">{{ saldo | currency('') }}</span>
            </span>
          </h2>
            <div class="pull-right">
              <router-link :to="{name: 'propietarios.editar', params: {id: casa.numero}}" class="btn btn-xs btn-primary">Editar <i class="fa fa-edit"></i></router-link>&nbsp;
             <router-link :to="{name: 'propietarios.estadocuenta', params: {casa: casa.numero}}" class="btn btn-xs btn-success">Ver estado de cuenta <i class="fa fa-edit"></i></router-link>&nbsp;
            </div>
          
        </div>
        <!-- /.col -->
      </div>
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">  
            <strong>
            <span v-if="!propietario.inquilino">Propietario</span>
            <span v-else>Inquilino</span></strong>
            <br>
            <strong>Cedula: </strong>{{ propietario.cedula | currency('', 0) }}<br>
            <strong>Apellidos: </strong>{{ propietario.apellidos }}<br>
            <strong>Nombres: </strong>{{ propietario.nombres }}<br>
            <strong>Fech. Nac.: </strong>{{ propietario.fecnac |formatDate('DD/MM/YYYY') }}<br>
            <strong>Hijos: </strong>{{ hijos.length }}<br>
            <strong>Vehiculos: </strong>{{ vehiculos.length }}<br>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col" v-if="conyuge != null">
            <strong>Conyuge</strong>
            <br>
            <strong>Cedula: </strong>{{ conyuge.cedula  | currency('', 0)}}<br>
            <strong>Apellidos: </strong>{{ conyuge.apellidos }}<br>
            <strong>Nombres: </strong>{{ conyuge.nombres }}<br>
            <strong>Fech. Nac.: </strong>{{ conyuge.fecnac |formatDate('DD/MM/YYYY') }}<br>
        </div>
        <div class="col-sm-4 invoice-col">
       <!-- <router-link :to="{name: 'propietarios.estadocuenta', params: {casa: casa.numero}}" class="btn btn-success">Estado de cuenta <i class="fa fa-list"></i></router-link>-->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <br>
      <div class="row">
        <div class="col-xs-12 table-responsive">
            <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active" v-if="hijos.length > 0"><a aria-expanded="true" href="#hijos" data-toggle="tab">Hijos</a></li>
              <li :class="hijos.length == 0 ? 'active' : ''" v-if="vehiculos.length > 0"><a :aria-expanded="hijos.length == 0 ? 'true' : 'false'" href="#vehiculos" data-toggle="tab">Vehiculos</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="hijos" v-if="hijos.length > 0">
                <table class="table table-striped table-responsive">
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
                        <td>{{ hijo.cedula |currency('', 0) }}</td>
                        <td class="cap">{{ hijo.apellidos }}</td>
                        <td class="cap">{{ hijo.nombre }}</td>
                        <td>{{ hijo.fecnac | formatDate('DD/MM/YYYY')}}</td>
                        <td>{{ hijo.sexo }}</td>
                        <td>{{ hijo.grado_estudio }}</td>
                      </tr>
                    </tbody>
                </table>
              </div>
              <div :class="hijos.length == 0 ? 'tab-pane active' : 'tab-pane'" id="vehiculos" v-if="vehiculos.length > 0">
               <table class="table table-striped table-responsive">
                    <thead>
                      <th>#</th>
                      <th>Marca</th>
                      <th>Modelo</th>
                      <th>Año</th>
                      <th>Placa</th>
                    </thead>
                    <tbody>
                      <tr v-for="(v, index) in vehiculos">
                        <td>{{ index + 1 }}</td>
                        <td class="cap">{{ v.marca }}</td>
                        <td class="cap">{{ v.modelo }}</td>
                        <td>{{ v.anio }}</td>
                        <td>{{ v.placa }}</td>
                      </tr>
                    </tbody>
                </table>
              </div>
            </div>
            <!-- /.tab-content -->
      </div>
        </div>
      </div>
    </section>
    <loading v-if="loading">
    <h4 slot="mensaje">Cargando informacion...</h4>  
  </loading>

		</div>
	</div>
</template>
<script>
 import router from '../../routes';
 import auth from '../../services/auth';
 import Ruta from '../Ruta.vue';
 import loading from '../loading.vue';
  export default {
    data () {
      return {
        existe: true,
        casa: {},
        propietario: {cedula: '', apellidos: '', nombres: '', fecnac: '', inquilino: false},
        conyuge: {},
        hijos: [],
        vehiculos: [],
        auth:auth,
        saldo: 0,
        loading: false,
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
        this.loading = true;
        this.existe = false;
        this.hijos = [];
        this.vehiculos = [];
        this.$http.get('/api/casas/'+this.$route.params.id).then(response => {
            var data = response.body.propietario[0];
            //console.log(data.casa)
            var saldo = response.body.saldo;
            if(response.body.propietario.length == 0){
              this.existe = false;
               this.loading = false;
            }else{
              this.existe = true;
              this.casa = data.casa;
              this.conyuge = data.conyuge;

              for (var i = 0; i < data.hijos.length; i++) {
                this.hijos.push(data.hijos[i]);
              };

              for (var i = 0; i < data.vehiculos.length; i++) {
                this.vehiculos.push(data.vehiculos[i]);
              };
              //data propietario
              this.propietario.cedula = data.cedula;
              this.propietario.apellidos = data.apellidos;
              this.propietario.nombres = data.nombres;
              this.propietario.fecnac = data.fecnac;
              this.propietario.inquilino = data.inquilino;
              this.existe = true;
              this.saldo = saldo;
              this.loading = false;
            }

        }, response => {
            //console.log(error);
            this.loading = false;
        })
      }
    },
    components: {
      Ruta,
      loading
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
 .red {color: #f56954;},
 .verde {color: #00a65a;}
</style>