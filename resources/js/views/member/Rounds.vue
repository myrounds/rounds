<template>
    <div class="my-rounds">
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid">

            <br>
            <spinner v-bind:loading="loading"></spinner>

            <div v-for="task in tasks" class='task-btn'>
                <div class="mui-panel" v-bind:tasks="JSON.stringify(task.items)" @click="showDetails">
                    <div class="non-selectable">
                        <span class="name">
                            <strong> {{ task.name }} </strong> &bull; {{ task.items.length }} items
                        </span>
                        <span class="time">{{ task.time }}</span>
                        <span class="address">{{ task.address }}</span>
                    </div>
                </div>
               <!-- <div class='divider'><i class='fas fa-caret-down'></i></div> -->
            </div>

            <div class="mui-panel">
                <i class="fas fa-check-circle"></i> All Rounded up for Today
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import DateTime from '../../helpers/datetime';
    import Spinner from '../../components/spinner';
    export default {
        components: {Spinner},
        data() {
            return {
                loading: false,
                tasks: null,
                error: null,
                day: null
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
                const tasks = JSON.parse(event.target.getAttribute('tasks') || event.target.parentNode.getAttribute('tasks'));
                const modalEl = document.createElement('div');

                modalEl.style.width = '90%';
                modalEl.style.minHeight = '300px';
                modalEl.style.margin = '100px auto';
                modalEl.style.backgroundColor = '#fff';
                modalEl.style.padding = '20px';
                modalEl.innerHTML += `
                    <legend>Task Items</legend>
                    ${tasks.map(task => {
                        return `
                        <div class="mui--divider-top">
                            <div>
                                <strong>
                                    ${task.name} | ${task.quantity} | ${task.complete ? 'completed' : 'pending'}
                                </strong>
                                <div>
                                    ${task.notes}
                                </div>
                            </div>
                        </div>`;
                    })}
                `;

                // show modal
                mui.overlay('on', modalEl);
            }
        }
    }
</script>