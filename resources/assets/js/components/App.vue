<template>
<div id="wrapper">
 <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>V</b>R</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Villa</b>Rita</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu" v-if="auth.user.authenticated">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ auth.user.profile.name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  {{ auth.user.profile.name }}
                  <small>Administrador</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a @click="logout" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="navbar-custom-menu" v-else>
      <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs">Iniciar Sesion</span>
              <i class="fa fa-user"></i>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
              <br><br>
                <form autocomplete="off" v-on:submit="signin">
                  <div class="form-group has-feedback">
                    <input type="text" id="email" class="form-control" placeholder="usuario" v-model="username" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" id="password" class="form-control" v-model="password" placeholder="Contrasela" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                  <div class="alert alert-danger" v-if="error">
            <p>There was an error, unable to sign in with those credentials.</p>
        </div>
                
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <button type="submit" @click="signin" class="btn btn-default btn-flat">Entrar</button>
                </div>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
	 <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div v-if="auth.user.authenticated">
        <div class="user-panel" v-if="auth.user.authenticated">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ auth.user.profile.name }}</p>
          <small>Ultima sesion: 24/02/2017</small>
        </div>
      </div>
      <!-- search form -->
      <form class="sidebar-form" v-on:submit.prevent="buscarCasa">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="Buscar..." v-model="buscar">
              <span class="input-group-btn">
                <button type="submit"  class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      </div>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENU PRINCIPAL</li>
        <li :class="$route.path == '/' ? 'active': ''"><router-link to="/"><i class="fa fa-home"></i> <span>Inicio</span></router-link></li>
        <li v-if="auth.user.authenticated && auth.user.profile.rol != 5" :class="$route.path == '/propietarios' ? 'active': ''"><router-link to="/propietarios"><i class="fa fa-group"></i> <span>Censo</span></router-link></li>
        <li :class="$route.path == '/admin' ? 'active': ''" v-if="rol_administrador || rol_informatica"><router-link to="/admin"><i class="fa fa-dashboard"></i> <span>Panel del control</span></router-link></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
      <transition name="fade">
        <router-view></router-view>
      </transition>
    </section>
    <!-- /.content -->
  </div>
 </div>
</template>
<script>
  import auth from '../services/auth';
  import Ruta from './Ruta.vue';
  import router from '../routes';
  export default {
    data() {
            return {
                auth: auth,
                admin: false,
                username: null,
                password: null,
                error: false,
                buscar: ''
            }
        },
        mounted(){
                auth.check()
        },
        computed: {
          rol_informatica: function(){

            if(auth.user.authenticated && auth.user.profile.rol == 1){
              return true
            }

            return false
          },
          rol_administrador: function(){

            if(auth.user.authenticated && auth.user.profile.rol == 2){
              return true
            }

            return false
          }
        },
        methods: {
            logout() {
                auth.signout()
            },
            signin(event) {
                event.preventDefault()
                auth.signin(this, this.username, this.password)
            },
            buscarCasa: function(){

                if(this.buscar == ''){
                  return false
                }

                router.push({name: 'propietarios.id', params: {id: this.buscar} });

                this.buscar = '';
            }
        },
        components: {
          Ruta
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