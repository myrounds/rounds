import Vue from 'vue'
import VueRouter from 'vue-router'
import Msg from 'vue-message'

Vue.use(VueRouter);
Vue.use(Msg);


import App from './components/App'
import routes from './routes'

const router = new VueRouter({
    mode: 'history',
    routes,
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});