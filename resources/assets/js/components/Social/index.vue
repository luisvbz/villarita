<template>
<div>
  <div class="row">
    <div class="col-lg-12">
      <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Administración Social</h3>
            </div>
            <div class="box-body">
               <router-link :to="{name: 'noticias.admin'}" class="btn btn-app">
                <i class="fa fa-file"></i></i> Noticias
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
