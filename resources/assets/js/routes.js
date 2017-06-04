import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: "active",
    saveScrollPosition: true,
    routes: [
    	{path:'/', component: require('./components/Webpage.vue')},
        {path: '/estadodecuenta', name: 'mi.estadocuenta', component: require('./components/Propietarios/EstadoCuenta.vue')},
    	//{path:'/login', component: require('./components/Login.vue')},
        {path:'/propietarios', component: require('./components/Propietarios/Index.vue')},
        {path:'/propietarios/nuevo', component: require('./components/Propietarios/New.vue')},
        {path:'/propietarios/:id', name:'propietarios.id', component: require('./components/Propietarios/Show.vue')},
        {path:'/propietarios/:id/editar', name:'propietarios.editar', component: require('./components/Propietarios/Editar.vue')},
        {path: '/propietarios/:casa/estadodecuenta', name: 'propietarios.estadocuenta', component: require('./components/Propietarios/EstadoCuenta.vue')},
        {path:'/mantenimiento', name: 'mmto', component: require('./components/Mantenimiento/Index.vue'),
            children: [
                {path: 'aniofiscal', name: 'afiscal', component: require('./components/Mantenimiento/Fiscal/Index.vue')},
                {path: 'periodos', name: 'periodos', component: require('./components/Mantenimiento/Periodos/Index.vue')},
                {path: 'cuentas', name: 'cuentas', component: require('./components/Mantenimiento/Bancos/Index.vue')},
                {path: 'tipoingresos', name: 'tipo.ingresos', component: require('./components/Mantenimiento/Ingresos/Index.vue')},
                {path: 'tipoegresos', name: 'tipo.egresos', component: require('./components/Mantenimiento/Egresos/Index.vue')},
                {path: 'usuarios', name: 'users', component: require('./components/Mantenimiento/Usuarios/Index.vue')}
            ]},
        { path: '/administracion', name: 'admin', component: require('./components/Administracion/Index.vue'),
            children: [
                {path: 'ingresos', name: 'admin.ingresos', component: require('./components/Administracion/Ingresos/Index.vue')},
                {path: 'conban', name: 'admin.conban', component: require('./components/Administracion/Ingresos/Consolidacion.vue')},
                {path: 'estadodecuenta/:casa', name: 'admin.estcuenta', component: require('./components/Propietarios/EstadoCuenta.vue')}
                //{path: 'ingresos', name: 'admin.ingresos', component: require('./components/Administracion/Ingresos/Index.vue')}
            ]},
        { path: '*', redirect: '/'}
    ]
})


export default router