<template>
  <transition name="load">
      <div class="load-mask">
        <div class="load-wrapper">
          <div class="load-container">
            <div class="load-body">
              <img src="/img/logo_xsmall.png" style="width: 100%;">
                <center>
                <pulse-loader color="#94c120"></pulse-loader>
                  <slot name="mensaje">
                  <h4>Entrando, por favor espere...</h4>
                  </slot>
                </center>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </template>
  <script>
  import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
  export default {
    name: 'loading',
    components:{
      PulseLoader
    }
  }
  </script>
  <style>
  .load-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.load-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.load-container {
  width: 300px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.load-header h3 {
  margin-top: 0;
  color: #42b983;
}

.load-body {
  margin: 20px 0;
}

.load-default-button {
  float: right;
}

.load-enter {
  opacity: 0;
}

.load-leave-active {
  opacity: 0;
}

.load-enter .load-container,
.load-leave-active .load-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

.cerrar{
    position: absolute;
  }
  </style>}
