<template>
    <div class="my-rounds">
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid">

            <br>
            <spinner v-bind:loading="loading"></spinner>



            <div v-for="task in tasks" class='task-btn'>
                <div class="mui-panel" v-bind:id="task.id" @click="showDetails">
                    <div class="non-selectable">
                        <span class="name">
                            <strong> {{ task.name }} </strong> &bull; {{ task.items.length }} items
                        </span>
                        <span class="time">{{ task.time }}</span>
                        <span class="address">{{ task.address }}</span>
                    </div>
                </div>
               <!--  <div style="width: 100%; text-align: center; margin-top: -20px; color: #aaa;">v</div> -->
            </div>

            <div class="mui-panel">
                <i class="fas fa-check-circle"></i> All Rounded up for Today
            </div>
        </div>



        <modal v-if="showModal" @close="showModal = false">
            <h3 slot="header">
                {{selected.name}}
            </h3>
            <div slot="body">


                <GmapMap
                    :center="{ lat: selected.lat, lng: selected.lon }"
                    :zoom="12"
                    map-type-id="terrain"
                    style="width: 100%; height: 300px">
                    <GmapMarker
                        :position="{ lat: selected.lat, lng: selected.lon }"
                        :clickable="true"
                        :draggable="true" />
                </GmapMap>

                <div class="directions-btn">
                    <button
                        class="mui-btn mui-btn--raised"
                        :lat="selected.lat"
                        :lon="selected.lon"
                        @click="goToDirections">Directions</button>
                </div>

                <br>

                <div class="mui-checkbox">
                    <label>
                        <input type="checkbox" value="" :checked="selected.completed_at">
                        Task Complete
                    </label>
                </div>

                <div>Address: {{selected.address}}</div>
                <div>Time: {{selected.time}}</div>
                <div>Day: {{selected.day}}</div>
                <div>Phone: {{selected.phone}}</div>
                <div>Email: {{selected.email}}</div>
                <div>Notes: {{selected.notes}}</div>
                <button
                    class="mui-btn mui-btn--small mui-btn--raised"
                    @click="leaveComment">Add Notes</button>

                <br><br>

                <h5>Items</h5>
                <div v-for="item in selected.items" class='item'>
                    <div class="mui--divider-top">
                        <div>
                            <strong>{{item.name}} | qnt: {{item.quantity}}</strong>

                            <div class="mui-checkbox">
                                <label>
                                    <input type="checkbox" value="" :checked="item.completed_at">
                                    Item Complete
                                </label>
                            </div>

                            <div>Notes: {{item.notes}}</div>

                            <button
                                class="mui-btn mui-btn--small mui-btn--raised"
                                @click="leaveComment">Add Notes</button>
                        </div>
                    </div>
                </div>


            </div>
        </modal>


    </div>
</template>

<script>
    import axios from 'axios';
    import DateTime from '../../helpers/datetime';
    import Spinner from '../../components/spinner';
    import Modal from '../../components/modal';
    export default {
        components: {Spinner, Modal},
        data() {
            return {
                loading: false,
                tasks: null,
                error: null,
                day: null,
                showModal: false,
                markers: {

                },
                selected: {}
            };
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.error = this.groups = [];
                this.loading = true;
                this.day = DateTime.getCurrentDay();

                axios
                    .get('/api/tasks/search', {
                        params: {
                            s_day: this.day,
                            e_day: this.day,
                        }
                    })
                    .then(response => {
                        this.loading = false;
                        const payload = response.data;

                        this.tasks = payload.data;
                    })
                    .catch(error => {
                        this.loading = false;
                        const payload = error.response.data;

                        if (payload.message) {
                            this.$msg(payload.message);
                        }
                    });
            },
            showDetails(event) {
                const id = event.target.id;
                const task = this.tasks.find(task => parseInt(task.id) === parseInt(id));

                this.selected = task;
                this.showModal = true;
            },
            goToDirections(event) {
                const lat = event.target.getAttribute('lat');
                const lon = event.target.getAttribute('lon');
                const userLocation = '-34.0625349,18.4460065'; // TODO: get from localStorage
                const url = `https://www.google.com/maps/dir/${userLocation}/${lat},${lon}`;

                window.open(url, '_blank');
            },
            leaveComment() {
                console.log('make comment input/ textarea visible');
            }
        }
    }
</script>