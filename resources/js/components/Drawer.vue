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
            </li>

            <li v-if="type != null">
                <div @click="toggleScheduleMembers">
                    <router-link class="section" :to="{ name: 'schedule' }">Schedule</router-link>
                </div>
                <ul v-if="type === 'account'" :class="{ 'hidden': !showScheduleMembers }">
                    <li class="sub-section" @click="filterMembers">
                        <a>All Members</a>
                    </li>
                    <li v-for="member in members" class="sub-section" @click="filterMembers" :id="member.id">
                        <a class="non-selectable">{{member.name}}</a>
                    </li>
                </ul>
                <router-link
                    class="section"
                    v-if="type === 'account'"
                    :to="{ name: 'members' }">Members</router-link>
            </li>

            <li v-if="type">
                <a class="section" @click="logout">Logout</a>
            </li>
        </ul>

        <!--<div id='map'><b>Locate Yourself</b></div>-->

    </div>
</template>

<script>
    import storage from '../helpers/storage';
    import Events from '../helpers/events';
    export default {
        data() {
            return {
                type: null,
                members: [],
                showScheduleMembers: false
            };
        },
        created() {
            const user = storage.get('user');
            if (user && user.type) {
                this.type = user.type
            }
            Events.addListener('account-changed', data => { this.type = data.user.type });

            const members = storage.get('members');
            if (members) {
                this.members = members;
            }
            Events.addListener('members-changed', data => { this.members = data.members })
        },
        methods: {
            logout() {
                storage.clear('user');
                storage.clear('members');
                this.type = null;
                this.members = null;
                this.$router.push({name: "login"});
                this.$msg('Logout successful');
            },
            filterMembers(event) {
                const id = event.target.id;
                this.$router.push(`/schedule/${id}`);
            },

            toggleScheduleMembers() {
                this.showScheduleMembers = !this.showScheduleMembers;
            }
        }
    }

    // $(document).ready(() => {
    //     mapboxgl.accessToken = 'pk.eyJ1IjoibGF1bmRyIiwiYSI6ImNqdzBpYXEydTBiZzk0YXBncDlzZjV4Z2wifQ.3oRSPYA0aPENMmqjjE3zQA';
    //     const map = new mapboxgl.Map({
    //         container: 'map', // container id
    //         style: 'mapbox://styles/mapbox/streets-v11',
    //         zoom: 3 // starting zoom
    //     });
    //
    //     // Add geolocate control to the map.
    //     map.addControl(new mapboxgl.GeolocateControl({
    //         positionOptions: {
    //             enableHighAccuracy: true
    //         },
    //         trackUserLocation: true
    //     }));
    // });

</script>