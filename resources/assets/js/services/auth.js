import Vue from 'vue';
import router from '../routes';
export default {
    user: {
        authenticated: false,
        profile: null
    },
    check() {
        if (localStorage.getItem('id_token') !== null) {
            Vue.http.get(
                'api/user'
            ).then(response => {
                this.user.authenticated = true
                this.user.profile = response.data.data
            })
        }
    },
    register(context, name, username, password) {
        Vue.http.post(
            'api/register',
            {
                name: name,
                username: username,
                password: password
            }
        ).then(response => {
            context.success = true
        }, response => {
            context.response = response.data
            context.error = true
        })
    },
    signin(context, username, password) {
        Vue.http.post(
            'api/login',
            {
                username: username,
                password: password
            }
        ).then(response => {
            context.error = false
            localStorage.setItem('id_token', response.data.meta.token)
            Vue.http.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('id_token')

            this.user.authenticated = true
            this.user.profile = response.data.data

            this.check();
            router.push({
                path: '/'
            })
        }, response => {
            context.error = true
        })
    },
    signout() {
        localStorage.removeItem('id_token')
        this.user.authenticated = false
        this.user.profile = null

        router.push({
            path: '/'
        })
    }
}
