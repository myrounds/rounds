<template>
    <div>

        <appdrawer></appdrawer>

        <appheader></appheader>

        <div id="content-wrapper">
            <div class="container">
                <router-view></router-view>
            </div>
        </div>

        <!--<appfooter></appfooter>-->

    </div>
</template>
<script>
    import axios from 'axios';
    import storage from '../helpers/storage';
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
        }
    }
</script>