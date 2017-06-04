<template>
<div>
  <Ruta :title="'Propietarios'" :subtitle="'/Lista'"></Ruta>
  <br>
    <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Lista de propietarios - <strong>{{ propietarios.length }}</strong> Registros</h3>
              <router-link to="/propietarios/nuevo" class="btn btn-info pull-right">Agregar <i class="fa fa-plus"></i></router-link>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-reponsive table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Calle</th>
                    <th>Casa</th>
                    <th>Cedula</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Telefono</th>
                    <th colspan="3">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(casa, index) in propietarios">
                    <td>{{ index + 1 }}</td>
                    <td>{{ casa.calle }}</td>
                    <td>{{ casa.numero }}</td>
                    <td>{{ casa.propietario.cedula | currency('', 0) }}</td>
                    <td>{{ casa.propietario.apellidos }}</td>
                    <td>{{ casa.propietario.nombres }}</td>
                    <td>{{ casa.propietario.telefono1 | phone }}</td>
                    <td><router-link :to="'/propietarios/'+casa.numero" 
                                      class="btn btn-sm btn-primary"
                                      rel="tooltip" title="Ver ficha completa"><i class="fa fa-search-plus"></i></router-link>
                        <router-link :to="{name: 'propietarios.editar', params: {'id': casa.numero }}"
                                      class="btn btn-sm btn-success">
                                      <i class="fa fa-edit"></i></router-link>
                      </td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
          </div>
          <div class="overlay" v-if="loading">
              <i class="fa fa-refresh fa-spin"></i>
           </div>
       <!-- /.box -->
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
        propietarios: [],
        loading: false,
        auth:auth
      }
    }, 
    mounted(){
      
      if(!auth.user.authenticated || auth.user.profile.rol == 3)
      {
        return router.push({path: '/'})
      }
      this.loading = true;
       this.$http.get('/api/casas').then(response => {
            for (var i = 0; i < response.body.length; i++) {
              var data = response.body[i];
              this.propietarios.push(data);
            }
            this.loading = false;
           // console.log(auth.user);

        }, response => {
            //console.log(error);
        })
    },
    components: {
      Ruta
    }
  }
</script>