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

            <!--<li v-if="type === 'member'">-->
                <!--<router-link class="section" :to="{ name: 'member.rounds' }">My Rounds</router-link>-->
            <!--</li>-->

            <li>
                <router-link class="section" :to="{ name: 'schedule' }">Schedule</router-link>
                <ul v-if="type === 'account'">
                    <li class="sub-section" @click="filterMembers">
                        <a>All Members</a>
                    </li>
                    <li v-for="member in members" class="sub-section" @click="filterMembers" :id="member.id">
                        <a class="non-selectable">{{member.name}}</a>
                    </li>
                </ul>
                <router-link class="section" :to="{ name: 'account.members' }">Members</router-link>
            </li>

            <li v-if="type">
                <a class="section" @click="logout">Logout</a>
            </li>
        </ul>

        <!--<div id='map'><b>Locate Yourself</b></div>-->

    </div>
</template>

<script>
    import axios from 'axios';
    import storage from '../helpers/storage';
    export default {
        data() {
            return {
                type: null,
                members: []
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

            jQuery(($) => {
                const $sidedrawerEl = $('#sidedrawer');

                const $titleEls = $('a', $sidedrawerEl);

                // $titleEls
                //     .next()
                //     .hide();

                // $titleEls.on('click', () => {
                //     console.log("TEST");
                //     $(context).next().slideToggle(200);
                // });
            });

            if (this.type === 'account') {
                this.getMembers();
            }
        },
        methods: {
            logout() {
                storage.clear('user');
                storage.clear('members');
                this.type = null;
                this.$router.push({name: "login"});
                this.$msg('Logout successful');
            },
            getMembers() {
                axios
                    .get('/api/members/search', {
                        params: {
                            name: ''
                        }
                    })
                    .then(response => {
                        this.loading = false;
                        const payload = response.data;
                        const members = payload.data;

                        this.members = members;
                        storage.set('members', members);
                    })
                    .catch(error => {
                        this.loading = false;
                        const payload = error.response.data;

                        if (payload.message) {
                            this.$msg(payload.message);
                        }
                    });
            },
            filterMembers(event) {
                const id = event.target.id;

                document.dispatchEvent(new CustomEvent("member-filter-changed", { "detail": { id } }));
                this.$router.push(`/schedule/${id}`);
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