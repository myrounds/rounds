<template>
    <div class="schedule-week">
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid week-grid">

            <!--<spinner v-bind:loading="loading"></spinner>-->

            <div class="mui-row">

                <div class="day-col" v-for="day in days" :style="{ 'width': dayColumnWidth }">

                    <h4>
                        <i class="fas fa-plus-circle task-create-btn" v-if="members" @click="showCreateTask"></i>
                        <i class="fas fa-book-open"></i> {{day}} <span><b>{{tasksFiltered[day] ? tasksFiltered[day].length : 0}}</b></span>
                        

                    </h4>
                    <div class="day-rows">
                        <div class="day-row" v-for="task in tasksFiltered[day]" :id="task.id" @click="showDetails">
                            <div class="non-selectable">
                                
                                <div class='round_title'>{{task.name}}</div>
                                <div class='round_timeAdd'><b>{{task.time.substring(0,5)}}</b> {{task.address}}</div>
                                <!--// todo: IF user.type === member-->
                                <!-- - show distance from current location for first item in list-->
                                <!-- - show distance from previous locations for remaining distances-->

                                <div v-if="members" class='round_user'>
                                    <i class='fas fa-user'></i> {{members.find(m => m.id === task.member_id).name}}
                                </div>
                            </div>
                        </div>
                        <div v-if="!tasksFiltered[day] || tasksFiltered[day].length === 0">
                            <div class="day-row roundedUp">
                                <p>
                                    All Rounded Up
                                </p>

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

                <div class="task-modal-block">
                    <!-- name text input -->
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="text">
                        <label>Task Name</label>
                    </div>

                    <!-- time select -->
                    <div>

                        <!--<div class="mui-select day">-->
                            <!--<select>-->
                                <!--<option value="monday">Monday</option>-->
                                <!--<option value="tuesday">Tuesday</option>-->
                                <!--<option value="wednesday">Wednesday</option>-->
                                <!--<option value="thursday">Thursday</option>-->
                                <!--<option value="friday">Friday</option>-->
                                <!--<option value="saturday">Saturday</option>-->
                                <!--<option value="sunday">Sunday</option>-->
                            <!--</select>-->
                            <!--<label>Day</label>-->
                        <!--</div>-->

                        <div class="mui-select hour">
                            <select>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            <label>Hour</label>
                        </div>
                        <div class="mui-select min">
                            <select>
                                <option value="00">00</option>
                                <option value="01">01</option>
                            </select>
                            <label>Min</label>
                        </div>
                        <div class="mui-select am-pm">
                            <select>
                                <option value="am">AM</option>
                                <option value="pm">PM</option>
                            </select>
                            <label></label>
                        </div>

                        <!-- repeat checkbox -->
                        <div class="mui-checkbox repeat">
                            <label>
                                <input type="checkbox" value="">
                                Repeat Weekly
                            </label>
                        </div>
                    </div>

                    <!-- address text input -->
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="text">
                        <label>Place/ Address</label>
                    </div>

                    <!-- email text input -->
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="email">
                        <label>Contact Email</label>
                    </div>

                    <!-- phone text input -->
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="number">
                        <label>Contact Phone</label>
                    </div>

                    <!-- member text input -->
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="text">
                        <label>Assign Member</label>
                    </div>
                </div>

                <div class="task-modal-block">
                    <!-- notes textarea -->
                    <div class="mui-textfield notes">
                        <textarea placeholder="Notes"></textarea>
                    </div>
                </div>



                <div>
                    <!-- save button -->
                    <button class="mui-btn mui-btn--raised">Save</button>
                </div>

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