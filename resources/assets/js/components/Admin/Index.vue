<template>
<div>
	<div class="row">
		<div class="col-lg-12">
			<div class="box">
            <div class="box-header">
              <h3 class="box-title">Herramientas</h3>
            </div>
            <div class="box-body">
              <a class="btn btn-app">
                <i class="fa fa-calendar"></i> Año Fiscal
              </a>
              <a class="btn btn-app">
                <i class="fa fa-clock-o"></i> Periodos
              </a>
              <a class="btn btn-app">
                <i class="fa fa-list-ol"></i> Bienes
              </a>
              <a class="btn btn-app">
                <i class="fa fa-money"></i> Estado de cuenta
              </a>
              <a class="btn btn-app">
                <i class="fa fa-dollar"></i> Pagos
              </a>
              <router-link to="/admin/usuarios" class="btn btn-app">
                <span class="badge bg-purple">891</span>
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
