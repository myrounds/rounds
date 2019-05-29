import Vue from 'vue'
import VueRouter from 'vue-router'
import * as VueGoogleMaps from 'vue2-google-maps'
import Msg from 'vue-message'

Vue.use(VueRouter);
Vue.use(Msg);
Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyDcGBfGg0PP96dCdlWfpRe8wfmCJg1GC_E',
        libraries: 'places'
    }
});


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