<template>
    <div class="app">

        <appdrawer></appdrawer>

        <appheader></appheader>

        <div id="content-wrapper">
                <router-view></router-view>

        </div>

        <!--<appfooter></appfooter>-->

    </div>
</template>
<script>
    import axios from 'axios';
    import storage from '../helpers/storage';
    import Location from '../helpers/location';
    import Api from '../helpers/api';
    import Appdrawer from './Drawer.vue';
    import Appheader from './Header.vue';
    import Appfooter from './Footer.vue';

    export default {
        components: {
            Appdrawer,
            Appheader,
            Appfooter
        },
        created() {
            Api.init(axios, storage, this);
            Location.watchLocation(storage);
        }
    }
</script>