<template>
    <div class="schedule-week">
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid week-grid">

            <!--<spinner v-bind:loading="loading"></spinner>-->

            <div class="mui-row">

                <div class="day-col" v-for="day in days" :style="{ 'width': dayColumnWidth }">

                    <h4><i class="fas fa-book-open"></i> {{day}} <span><b>{{tasksFiltered[day] ? tasksFiltered[day].length : 0}}</b> Rounds</span></h4>

                    <div class="task-create-btn" v-if="members" @click="showCreateTask">
                        Add Task
                    </div>

                    <div class="day-rows">
                        <div class="day-row" v-for="task in tasksFiltered[day]" :id="task.id" @click="showDetails">
                            <div class="non-selectable">
                                
                                <div class='round_title'>{{task.name}}</div>
                                <div class='round_timeAdd'><b>{{task.time.substring(0,5)}}</b> {{task.address}}</div>
                                <!--// todo: IF user.type === member-->
                                <!-- - show distance from current location for first item in list-->
                                <!-- - show distance from previous locations for remaining distances-->

                                <div v-if="members">
                                    Assigned: {{members.find(m => m.id === task.member_id).name}}
                                </div>
                            </div>
                        </div>
                        <div v-if="!tasksFiltered[day] || tasksFiltered[day].length === 0">
                            <div class="day-row roundedUp">
                                <p>
                                    <b>Nice, You're all Rounded Up.</b>
                                    Here are some things to occupy your time, you could score yourself nice discounts!*
                                </p>
                                <ul>
                                    <li>Recommend Rounds to a Friend or Company?</li>
                                    <!-- <li>Write a Story on Facebook using the word Round 25 times?</li> -->
                                    <!-- <li>Play RoundSnake and Challenge other Users to a High Score?</li> -->
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>



        <modal v-if="showModal && selected" @close="showModal = false">
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
                        @click="directionsClicked">Directions</button>
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

                <div v-if="members">
                    <h5>Assignee</h5>
                    <div v-if="selectedMember">
                        {{selectedMember.name}}
                    </div>
                    <div v-if="!selectedMember">
                        Unassigned
                    </div>
                    <button
                        class="mui-btn mui-btn--small mui-btn--raised"
                        @click="assignMember">Assign Member</button>
                </div>

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




        <modal v-if="creatingTask" @close="creatingTask = false">
            <h3 slot="header">
                Add Task
            </h3>
            <div slot="body">

                TASK CREATE FORM GOES HERE

                <!-- text input -->
                <div class="mui-textfield mui-textfield--float-label">
                    <input type="text">
                    <label>Text Field</label>
                </div>

                <!-- textarea -->
                <div class="mui-textfield">
                    <textarea placeholder="Textarea"></textarea>
                </div>

                <!-- select -->
                <div class="mui-select">
                    <select>
                        <option value="option_1">Option 1</option>
                        <option value="option_2">Option 2</option>
                    </select>
                    <label>Type</label>
                </div>

                <!-- checkbox -->
                <div class="mui-checkbox">
                    <label>
                        <input type="checkbox" value="">
                        checkbox
                    </label>
                </div>

                <!-- button -->
                <button class="mui-btn mui-btn--raised">Save</button>

                <!--
                FOR MORE SUPPORTED FORM ELEMENTS, PLEASE SEE:
                - https://www.muicss.com/docs/v1/css-js/forms
                - https://www.muicss.com/docs/v1/css-js/buttons
                -->

            </div>
        </modal>



    </div>
</template>

<script>
    import axios from 'axios';
    import DateTime from '../helpers/datetime';
    import Storage from '../helpers/storage';
    import Events from '../helpers/events';
    import Location from '../helpers/location';
    import Spinner from '../components/spinner';
    import Modal from '../components/modal';
    export default {
        components: {Spinner, Modal},
        data() {
            return {
                loading: false,
                days: [],
                dayColumnWidth: '',
                tasksAll: [],
                tasksFiltered: {},
                members: {},

                showModal: false,
                markers: {},
                selected: {},
                selectedMember: {},

                creatingTask: false,

                error: null
            };
        },
        watch:{
            $route (to, from){
                this.memberId = to.params.memberId;
                this.filterTasks();
            }
        },
        created() {
            this.days = DateTime.getDaysOfWeek();
            this.memberId = this.$route.params.memberId;
            this.fetchData();

            Events.addListener("filters-changed", data => this.setFilters(data));
        },
        methods: {
            fetchData() {
                this.members = Storage.get('members');
                this.error = this.users = null;
                this.loading = true;

                axios
                    .get('/api/tasks/search', {
                        params: {
                            s_day: this.days[0],
                            e_day: this.days[this.days.length - 1],
                        }
                    })
                    .then(response => {
                        this.loading = false;
                        const payload = response.data;

                        let tasks = payload.data;
                        this.tasksAll = tasks.slice(0);
                        this.filterTasks();
                    })
                    .catch(error => {
                        this.loading = false;
                        const payload = error.response.data;

                        if (payload.message) {
                            this.$msg(payload.message);
                        }
                    });
            },

            setFilters(data) {
                const day = data.day;

                this.days = day ? [day] : DateTime.getDaysOfWeek();
                this.dayColumnWidth = this.days.length === 1 ? '100%' : '';
            },
            filterTasks() {
                let tasks = this.tasksAll.slice(0);
                if (this.memberId) {
                    tasks = tasks.filter(t => parseInt(t.member_id) === parseInt(this.memberId))
                }
                const tasksByDay = tasks.reduce((h, obj) => {
                    return Object.assign(h, { [obj.day]:( h[obj.day] || [] ).concat(obj) });
                }, {});

                this.tasksFiltered = tasksByDay;
            },

            showDetails(event) {
                const id = event.target.id;
                const task = this.tasksAll.find(task => parseInt(task.id) === parseInt(id));
                const members = Storage.get('members');

                this.selected = task;
                this.selectedMember = members ? members.find(member => member.id === task.member_id) : null;
                this.showModal = true;
            },
            directionsClicked(event) {
                const lat = event.target.getAttribute('lat');
                const lon = event.target.getAttribute('lon');
                const taskLocation = `${lat},${lon}`;

                Location.get(Storage).then(device => {
                    this.goToDirections(`${device.lat},${device.lon}`, taskLocation);
                }).catch(error => {
                    this.$msg(error);
                });
            },
            goToDirections(userLocation, taskLocation) {
                const url = `https://www.google.com/maps/dir/${userLocation}/${taskLocation}`;

                window.open(url, '_blank');
            },
            leaveComment() {
                console.log('make comment input/ textarea visible');
            },
            assignMember() {
                console.log('assign member to task');
            },


            showCreateTask() {
                this.creatingTask = true;
            }

        }
    }
</script>