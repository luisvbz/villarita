//Importando las librerias
import Vue from 'vue';
//import VueRouter from 'vue-router';
import VueResource from 'vue-resource';
import Vue2Filters from 'vue2-filters';
import VueSweetAlert from 'vue-sweetalert';
import Dialog from 'hsy-vue-dialog'
import router from './routes';
import auth from './services/auth';
import VueTheMask from 'vue-the-mask'
require('./filters');

Vue.use(VueResource)
Vue.use(Vue2Filters)
Vue.use(Dialog)
Vue.use(VueSweetAlert)
Vue.use(VueTheMask)


//Importando componentes
//auth.check();
import App from './components/App.vue';
import Login from './components/Login.vue';

Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('jwt-token');
Vue.http.options.root = 'http://condominiovillarita.com.ve';
Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

const app = new Vue({
    el: '#app',
    components: { App },
    template: '<app></app>',
    router
});
