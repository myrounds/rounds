<template>
    <div>
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid">

            <br>

            <div class="mui-panel">
                <form class="mui-form">
                    <legend>Login</legend>
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="email" v-model="email">
                        <label>Email</label>
                    </div>
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="password" v-model="password">
                        <label>Password</label>
                    </div>
                    <div class="mui-select">
                        <select v-model="type">
                            <option value="account">Account</option>
                            <option value="assignee">Assignee</option>
                        </select>
                        <label>Type</label>
                    </div>
                    <button type="button" class="mui-btn mui-btn--raised" v-on:click="attempt">Login</button>
                    <button type="button" class="mui-btn mui-btn--raised" v-on:click="register">Sing Up</button>
                </form>
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
                error: null,
                email: '',
                password: '',
                type: 'account'
            };
        },
        created() {
            const storedUser = window.localStorage.getItem('user');
            if (storedUser && typeof storedUser != null) {
                const user = JSON.parse(storedUser);
                if (user.type) {
                    this.type = user.type;
                    this.login(user);
                }
            }
        },
        methods: {
            attempt() {
                this.error = null;
                this.loading = true;
                axios
                    .post(`/api/${this.type}s/login`, {
                        category_id: 1,
                        email: this.email,
                        password: this.password,
                        type: this.type
                    })
                    .then(response => {
                        const payload = response.data;
                        const user = payload.data;
                        const userWithType = {...{type: this.type},...user};
                        this.login(userWithType);
                    })
                    .catch(error => {
                        console.log(error);
                        const payload = error.response.data;
                        this.$msg(payload.message);
                    });
            },
            login(user) {
                if (user != null && typeof user === 'object') {
                    window.localStorage.setItem('user', JSON.stringify(user));

                    const event = new CustomEvent("account-changed", { "detail": user });
                    document.dispatchEvent(event);

                    if (this.type === 'account') {
                        this.$router.push({name: "account.rounds"});
                        this.$msg('Login successful');
                    }
                    else if (this.type === 'assignee') {
                        this.$router.push({name: "assignee.rounds"});
                        this.$msg('Login successful');
                    } else {
                        this.$msg('Could not log in');
                    }
                }
            },
            register() {

            }
        }
    }
</script>