import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: "active",
    saveScrollPosition: true,
    routes: [
    	{path:'/', component: require('./components/Index.vue')},
    	//{path:'/login', component: require('./components/Login.vue')},
        {path:'/demo', component: require('./components/Demo.vue')},
        {path:'/propietarios', component: require('./components/Propietarios/Index.vue')},
        {path:'/propietarios/nuevo', component: require('./components/Propietarios/New.vue')},
        {path:'/propietarios/:id', name:'propietarios.id', component: require('./components/Propietarios/Show.vue')}
    ]
})


export default router