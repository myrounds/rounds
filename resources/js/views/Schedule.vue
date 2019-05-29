<template>
    <div class="schedule-week">
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid week-grid">
            <div class="mui-row">

                <div class="day-col" v-for="day in days">
                    <h4>{{day}}</h4>
                    <div class="day-rows">
                        <div class="day-row" v-for="task in tasksFiltered[day]">
                            {{task.time.substring(0,5)}}
                            <br>
                            {{task.name}}
                            <div v-if="members">
                                > {{members.find(m => m.id === task.member_id).name}}
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import DateTime from '../helpers/datetime';
    import Storage from '../helpers/storage';
    import Events from '../helpers/events';
    export default {
        data() {
            return {
                loading: false,
                days: [],
                tasksAll: [],
                tasksFiltered: {},
                members: {},
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
            setFilters(data) {
                const day = data.day;

                this.days = day ? [day] : DateTime.getDaysOfWeek();
            },
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
            filterTasks() {
                let tasks = this.tasksAll.slice(0);
                if (this.memberId) {
                    tasks = tasks.filter(t => parseInt(t.member_id) === parseInt(this.memberId))
                }
                const tasksByDay = tasks.reduce((h, obj) => {
                    return Object.assign(h, { [obj.day]:( h[obj.day] || [] ).concat(obj) });
                }, {});

                this.tasksFiltered = tasksByDay;
            }
        }
    }
</script>