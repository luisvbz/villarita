<template>
<div>
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Mantenimiento del sistema</h3>
            </div>
            <div class="box-body">
              <router-link to="/mantenimiento/aniofiscal"class="btn btn-app">
                <i class="fa fa-calendar"></i> Año Fiscal
              </router-link>
              <router-link to="/mantenimiento/periodos" class="btn btn-app">
                <i class="fa fa-clock-o"></i> Periodos
              </router-link>
              <router-link to="/mantenimiento/cuentas" class="btn btn-app">
                <i class="fa fa-money"></i> Cuentas
              </router-link>
              <router-link to="/mantenimiento/tipoingresos" class="btn btn-app">
                <i class="fa fa-dollar"><i class="fa fa-long-arrow-down"></i></i>Tipos de Ingresos
              </router-link>
              <router-link to="/mantenimiento/tipoegresos"  class="btn btn-app">
                <i class="fa fa-dollar"><i class="fa fa-long-arrow-up"></i></i> Tipos de Egresos
               </router-link>
               <router-link to="/mantenimiento/vigilantes" class="btn btn-app">
                <i class="fa fa-user"></i> Vigilantes
              </router-link>
              <router-link to="/mantenimiento/usuarios" class="btn btn-app">
                <i class="fa fa-users"></i> Users
              </router-link>
            </div>
            <!-- /.box-body -->
          </div>
		</div>
	</div>
  <transition name="fade">
	 <router-view ></router-view>
  </transition>
</div>
</template>
<script>
  export default{
    data () {
      return {

      }
    },
      methods: {
        getPdf: function(){

          this.$http.post('/api/pdf', {a: 'a'}, {responseType: 'arraybuffer'}).then(function(response){
              var headers = response.headers;
              var blob = new Blob([response.data], {type: headers['content-type']});
              var link = document.createElement('a');
              console.log(link);
              link.href = window.URL.createObjectURL(blob);
              link.download = "filename.pdf";
              link.click();
          });
        }
      }
  }
</script>
<style>
  .fade-enter-active, .fade-leave-active {
    transition-property: opacity;
    transition-duration: .25s;
  }

  .fade-enter-active {
    trasition-delay: .25s;
  }

  .fade-enter, .fade-leave-active{
    opacity: 0;
  }
</style>
