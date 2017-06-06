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
              <!--<img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
              <span class="hidden-xs"><b>{{ auth.user.profile.name }}</b>
              <i class="fa fa-user"></i>
              </span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="background-color: #B8B8B8;">
              <div class="img-circle icono">
                  {{ inicial }}
              </div>
                <p>
                  <b>{{ auth.user.profile.name }}</b>
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
              <li class="user-header" style="background-color: #B8B8B8;">
              <br><br>
                <form autocomplete="off" v-on:submit="signin">
                  <div class="form-group has-feedback">
                    <input type="text" id="email" class="form-control" placeholder="usuario" v-model="username" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" id="password" v-on:keydown.enter="signin" class="form-control" v-model="password" placeholder="Contrasela" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
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
        <div class="pull-left image icon">
          <!--<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
          <div class="img-circle">
              {{ inicial }}
          </div>
        </div>
        <div class="pull-left info">
          <p>{{ auth.user.profile.name }}</p>
          <small>Bienvenido(a)</small>
        </div>
      </div>
      <!-- search form -->
      <form class="sidebar-form" v-on:submit.prevent="buscarCasa" v-if="rol_informatica || rol_administrador">
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
        <li v-if="rol_administrador || rol_informatica" :class="$route.path == '/propietarios' ? 'active': ''"><router-link to="/propietarios"><i class="fa fa-group"></i> <span>Propietarios</span></router-link></li>
        <li v-if="rol_propietario" :class="$route.path == '/estadodecuenta' ? 'active': ''"><router-link to="/estadodecuenta"><i class="fa fa-money"></i> <span>Mi estado de cuenta</span></router-link></li>
        <li v-if="rol_propietario" :class="$route.path == '/pagos' ? 'active': ''"><router-link to="/pagos"><i class="fa fa-dollar"></i> <span>Mis pagos</span></router-link></li>
        <li :class="$route.path == '/mantenimiento' ? 'active': ''" v-if="rol_administrador || rol_informatica"><router-link to="/mantenimiento"><i class="fa fa-dashboard"></i> <span>Mantenimiento</span></router-link></li>
        <li :class="$route.path == '/administracion' ? 'active': ''" v-if="rol_administrador || rol_informatica"><router-link to="/administracion"><i class="fa fa-tasks"></i> <span>Administracion</span></router-link></li>
        <li><a href="/reglamento.pdf"><i class="fa fa-file"></i> <span>Reglamento</span></a></li>
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
  <load v-if="showModal" @close="showModal = false">
    <h3 slot="header"></h3>
    <div slot="body">
      <center><i class="fa fa-spinner fa-spin" style="color: #00a65a; font-size:80px;"></i>
      <h4>Entrando, por favor espere...</h4>
    </div></center> 
</load>
 </div>
</template>
<script>
  import auth from '../services/auth';
  import Ruta from './Ruta.vue';
  import router from '../routes';
  import load from '../components/load.vue';
  export default {
    data() {
            return {
                auth: auth,
                admin: false,
                username: null,
                password: null,
                error: false,
                buscar: '',
                showModal: false
            }
        },
        mounted(){
                auth.check()
                console.log(this.auth.user.profile)
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
          },
          rol_propietario: function(){

            if(auth.user.authenticated && auth.user.profile.rol == 3){
              return true
            }

            return false
          },

          inicial: function(){

            let inicial = this.auth.user.profile.name

            return inicial.substr(0,1);
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
            },
            errorUser: function(){
                this.$swal({
                title:'Error!',
                text: 'Usuario y/o contraseña incorrectos',
                type: 'error'
              })
            }
        },
        components: {
          Ruta,
          load
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

  .icon{
      background-color: #00a65a;
      color: #fff;
      font-size: 35px;
      width: 50px;
      border-radius: 50%;
      text-align: center;
      text-transform: bold;
  }

   .icono{
      background-color: #333;
      color: #fff;
      font-size: 60px;
      border-radius: 50%;
      text-align: center;
      text-transform: bold;
      z-index: 5;
      height: 90px;
      width: 90px;
      vertical-align: middle;
      border: 3px solid;
      border-color: transparent;
      border-color: rgba(255,255,255,0.2);
      margin-left: 80px;
  }
</style>