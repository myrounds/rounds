<template>
    <div class="schedule-week">
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid week-grid">

            <div class="mui-row">

                <div class="day-col" v-for="day in days">
                    <h4>{{day}}</h4>
                    <div class="day-rows">
                        <div class="day-row" v-for="task in tasks[day]">
                            {{task.time.substring(0,5)}}
                            <br>
                            {{task.name}}
                            <br>
                            > {{members.find(m => m.id === task.member_id).name}}
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
    export default {
        data() {
            return {
                loading: false,
                days: [],
                tasks: {},
                members: {},
                error: null,
                type: ''
            };
        },
        created() {
            this.days = DateTime.getDaysOfWeek();
            this.fetchData();

            document.addEventListener("day-changed", (event) => {
                const day = event.detail.day;

                if (day) {
                    this.days = [day];
                } else {
                    this.days = DateTime.getDaysOfWeek();
                }
            });

            document.addEventListener("member-filter-changed", (event) => {
                const user = event.detail;

                this.type = user.id;

                this.fetchData();
            });
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

                        if (this.$route.params.memberId) {
                            tasks = tasks.filter(t => parseInt(t.member_id) === parseInt(this.$route.params.memberId))
                        }

                        const tasksByDay = tasks.reduce((h, obj) => {
                            return Object.assign(h, { [obj.day]:( h[obj.day] || [] ).concat(obj) });
                        }, {});

                        this.tasks = tasksByDay;
                        console.log("API REQUEST", this.tasks);
                    })
                    .catch(error => {
                        this.loading = false;
                        const payload = error.response.data;

                        if (payload.message) {
                            this.$msg(payload.message);
                        }
                    });
            }
        }
    }
</script>