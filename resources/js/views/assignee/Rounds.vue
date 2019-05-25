<template>
    <div class="my-rounds">
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid">

            <br>

            <div class="loading" v-if="loading">
                Loading...
            </div>

            <div v-for="group in groups">
                <div class="mui-panel" v-bind:tasks="JSON.stringify(group.tasks)" @click="showDetails">
                    <div>
                        <span class="time">{{ group.time }}</span> {{ group.name }} | {{ group.tasks.length }} items
                    </div>
                    <div class="mui--divider-top">
                        {{ group.address }}
                    </div>
                </div>
                <div style="width: 100%; text-align: center; margin-top: -20px; color: #aaa;">v</div>
            </div>

            <div class="mui-panel">
                DONE FOR DAY
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                loading: false,
                groups: null,
                error: null,
                groupId: null
            };
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.error = this.groups = [];
                this.loading = true;
                const Authorization = 'Bearer ' + JSON.parse(localStorage.getItem('user')).token;

                axios
                    .get('/api/groups/search', {
                        headers: { Authorization },
                        params: {
                            s_day: 'monday',
                            e_day: 'monday',
                        }
                    })
                    .then(response => {
                        const payload = response.data;
                        this.groups = payload.data;
                        this.loading = false;
                    })
                    .catch(error => {
                        const payload = error.response.data;
                        console.log(payload.message);
                        this.loading = false;
                    });
            },
            showDetails(event) {
                var modalEl = document.createElement('div');
                modalEl.style.width = '80%';
                modalEl.style.minHeight = '300px';
                modalEl.style.margin = '100px auto';
                modalEl.style.backgroundColor = '#fff';
                modalEl.style.padding = '20px';

                const tasks = JSON.parse(event.target.getAttribute('tasks') || event.target.parentNode.getAttribute('tasks'));
                console.log(tasks);

                modalEl.innerHTML += `<legend>Tasks</legend>`;
                tasks.forEach(task => {
                    modalEl.innerHTML += `<div class="mui--divider-top">`;
                    modalEl.innerHTML += `<div><strong>${task.name} | ${task.quantity} | ${task.complete ? 'completed' : 'pending'}</strong></div>`;
                    modalEl.innerHTML += `<div><em>${task.notes}</em></div>`;
                    modalEl.innerHTML += `</div>`;
                })

                // show modal
                mui.overlay('on', modalEl);
            }
        }
    }
</script>