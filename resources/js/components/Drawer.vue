<template>
    <div id="sidedrawer" class="mui--no-user-select">
        <div class="brand mui--appbar-line-height">
            <span class="mui--text-title">
                <img src="../../images/rounds.svg" class='rounds-logo' />
            </span>
        </div>
        <div class="mui-divider"></div>
        <ul>
            <li v-if="!type">
                <router-link class="section" :to="{ name: 'login' }">Login</router-link>
                <!--<ul>-->
                <!--<li><a href="#">Item 1</a></li>-->
                <!--<li><a href="#">Item 2</a></li>-->
                <!--<li><a href="#">Item 3</a></li>-->
                <!--</ul>-->
            </li>

            <li v-if="type === 'member'">
                <router-link class="section" :to="{ name: 'member.rounds' }">My Rounds</router-link>
            </li>
            <li v-if="type === 'account'">
                <router-link class="section" :to="{ name: 'account.rounds' }">Schedule</router-link>
            </li>

            <li v-if="type">
                <a class="section" @click="logout">Logout</a>
            </li>
        </ul>

        <div id='map'><b>Locate Yourself</b></div>

    </div>
</template>

<script>
    import storage from '../helpers/storage';
    export default {
        data() {
            return {
                type: null
            };
        },
        created() {
            const context = this;

            const user = storage.get('user');
            if (user && user.type) {
                this.type = user.type
            }

            document.addEventListener("account-changed", (event) => {
                const user = event.detail;

                context.type = user.type;
            });

        },
        methods: {
            logout() {
                storage.clear('user');
                this.type = null;
                this.$router.push({name: "login"});
                this.$msg('Logout successful');
            }
        }
    }

    $(document).ready(() => {
        mapboxgl.accessToken = 'pk.eyJ1IjoibGF1bmRyIiwiYSI6ImNqdzBpYXEydTBiZzk0YXBncDlzZjV4Z2wifQ.3oRSPYA0aPENMmqjjE3zQA';
        const map = new mapboxgl.Map({
            container: 'map', // container id
            style: 'mapbox://styles/mapbox/streets-v11',
            zoom: 3 // starting zoom
        });

        // Add geolocate control to the map.
        map.addControl(new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: true
        }));
    });

</script>