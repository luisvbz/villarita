
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Importando las librerias
import Vue from 'vue';
//import VueRouter from 'vue-router';
import VueResource from 'vue-resource';
import Vue2Filters from 'vue2-filters';
import VueSweetAlert from 'vue-sweetalert';
import Dialog from 'hsy-vue-dialog'
import router from './routes';
import auth from './services/auth';
require('./filters');

Vue.use(VueResource)
Vue.use(Vue2Filters)
Vue.use(Dialog)
Vue.use(VueSweetAlert)


//Importando componentes
//auth.check();
import App from './components/App.vue';
import Login from './components/Login.vue';

Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('jwt-token');
Vue.http.options.root = 'http://localhost:8000';
Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

const app = new Vue({
    el: '#app',
    components: { App },
    template: '<app></app>',
    router
});
