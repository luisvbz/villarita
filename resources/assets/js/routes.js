import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: "active",
    saveScrollPosition: true,
    routes: [
    	{path:'/', component: require('./components/Webpage.vue')},
        //{path:'/reglamento', component: require('./components/Reglamento.vue')},
        {path: '/estadodecuenta', name: 'mi.estadocuenta', component: require('./components/Propietarios/EstadoCuenta.vue')},
        {path: '/pagos', name: 'mis.pagos', component: require('./components/Propietarios/Pagos.vue')},
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
                {path: 'usuarios', name: 'users', component: require('./components/Mantenimiento/Usuarios/Index.vue')},
                {path: 'vigilantes', name: 'vigilante', component: require('./components/Mantenimiento/Vigilantes/Index.vue')}
            ]},
        { path: '/administracion', name: 'admin', component: require('./components/Administracion/Index.vue'),
            children: [
                {path: 'ingresos', name: 'admin.ingresos', component: require('./components/Administracion/Ingresos/Index.vue')},
                {path: 'confirmarpagos', name: 'admin.conf', component: require('./components/Administracion/Ingresos/Pendientes.vue')},
                {path: 'estadodecuenta/:casa', name: 'admin.estcuenta', component: require('./components/Propietarios/EstadoCuenta.vue')},
                {path: 'conban', name: 'admin.conban', component: require('./components/Administracion/Ingresos/Consolidacion.vue'),
                    children:[
                        {path: 'detalles/:banco', name: 'detalles.conban', component: require('./components/Administracion/Ingresos/DetallesCon.vue')}  
                    ]
                },
                //{path: 'ingresos', name: 'admin.ingresos', component: require('./components/Administracion/Ingresos/Index.vue')}
            ]},
        { path: '/noticias', name: 'news', component: require('./components/Noticias/Index.vue')},
        { path: '/noticias/:slug', name: 'noticia.show', component: require('./components/Noticias/Show.vue')},
        { path: '/social', name: 'social', component: require('./components/Social/index.vue'),
            children: [
                {path: 'noticias', name: 'noticias.admin', component: require('./components/Social/Noticias/Index.vue')},
                {path: 'noticias/nueva', name: 'noticias.new', component: require('./components/Social/Noticias/Nueva.vue')},
            ]},
        { path: '*', redirect: '/'},
    ]
})


export default router