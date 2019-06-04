<template>
    <div id="sidedrawer" class="mui--no-user-select">
        <div id="sidedrawer-brand" class="mui--appbar-line-height">
            <span class="mui--text-title">
                <img src="img/rounds.svg" class='rounds_logo' />
            </span>
        </div>
        <div class="mui-divider"></div>
        <ul>
            <li v-if="!type">
                <router-link class="section" :to="{ name: 'login' }">Login</router-link>
                <ul>
                <li><a href="#">Item 1</a></li>
                <li><a href="#">Item 2</a></li>
                <li><a href="#">Item 3</a></li>
                </ul>
            </li>

            <li v-if="type === 'assignee'">
                <router-link class="section" :to="{ name: 'assignee.rounds' }">My Rounds</router-link>
            </li>
            <li v-if="type === 'account'">
                <router-link class="section" :to="{ name: 'account.rounds' }">Rounds</router-link>
            </li>

            <li v-if="type">
                <a class="section" @click="logout">Logout</a>
            </li>
        </ul>


<div id='map'><b>Locate Yourself</b></div>



    </div>
</template>

<script>
    export default {
        data() {
            return {
                type: null
            };
        },
        created() {
            const context = this;

            const storedUser = window.localStorage.getItem('user');
            if (storedUser != null) {
                const user = JSON.parse(storedUser);
                if (typeof user === 'object' && user.type) {
                    this.type = user.type
                }
            }

            document.addEventListener("account-changed", function(e){
                const user = e.detail;

                context.type = user.type;
            });

        },
        methods: {
            logout() {
                window.localStorage.removeItem('user');
                this.type = null;
                this.$router.push({name: "login"});
                this.$msg('Logout successful');
            }
        }
    }

// $(document).ready(function() { 



//     mapboxgl.accessToken = 'pk.eyJ1IjoibGF1bmRyIiwiYSI6ImNqdzBpYXEydTBiZzk0YXBncDlzZjV4Z2wifQ.3oRSPYA0aPENMmqjjE3zQA';
//     var user_location = ["174.907639","-36.873264"];
//     var map = new mapboxgl.Map({

//     container: 'map', // container id
//     style: 'mapbox://styles/mapbox/streets-v11',
//     center: user_location,
//     zoom: 3 // starting zoom
//     });
     
//     // Add geolocate control to the map.
//     map.addControl(new mapboxgl.GeolocateControl({
//     positionOptions: {
//     enableHighAccuracy: true
//     },
//     trackUserLocation: true
//     }));
// });

</script>