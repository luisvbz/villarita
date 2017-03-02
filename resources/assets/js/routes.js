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
        {path:'/propietarios', component: require('./components/Propietarios/Index.vue')},
        {path:'/propietarios/nuevo', component: require('./components/Propietarios/New.vue')},
        {path:'/propietarios/:id', name:'propietarios.id', component: require('./components/Propietarios/Show.vue')},
        {path:'/propietarios/:id/editar', name:'propietarios.editar', component: require('./components/Propietarios/Editar.vue')},
        {path:'/admin', name: 'admin', component: require('./components/Admin/Index.vue'),
            children: [
                {path: 'usuarios', name: 'users', component: require('./components/Admin/Usuarios/Index.vue')}
            ]}
    ]
})


export default router