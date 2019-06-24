<template>
    <div>
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid logged-out-page">

            <form class="mui-form">

                <div class="mui-textfield">
                    <input type="text" v-model="name" required>
                    <label>Name</label>
                    <div class="error" v-for="error in this.errors.name">{{error}}</div>
                </div>
                <div class="mui-textfield">
                    <input type="email" v-model="email" required>
                    <label>Email</label>
                    <div class="error" v-for="error in this.errors.email">{{error}}</div>
                </div>
                <div class="mui-textfield">
                    <input type="tel" v-model="phone" required>
                    <label>Phone</label>
                    <div class="error" v-for="error in this.errors.phone">{{error}}</div>
                </div>
                <div class="mui-textfield">
                    <input type="password" v-model="password" required>
                    <label>Password</label>
                    <div class="error" v-for="error in this.errors.password">{{error}}</div>
                </div>
                <div class="mui-textfield">
                    <input type="password" v-model="repeat_password" required>
                    <label>Repeat Password</label>
                    <div class="error" v-for="error in this.errors.repeat_password">{{error}}</div>
                </div>
                <button type="button" class="mui-btn login_btn" v-on:click="register">
                    Register
                </button>
            </form>
            
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                loading: false,
                name: '',
                email: '',
                phone: '',
                password: '',
                repeat_password: '',
                type: 'account',
                errors: {}
            };
        },
        methods: {
            register(event) {
                event.preventDefault();
                this.errors = {};
                axios
                    .post(`/api/accounts/create`, {
                        category_id: 1,
                        name: this.name,
                        email: this.email,
                        phone: this.phone,
                        password: this.password,
                        repeat_password: this.repeat_password,
                    })
                    .then(response => {
                        const payload = response.data;
                        const user = payload.data;

                        this.$router.push({name: "login.account"});
                        this.$msg("Account created successfully");
                    })
                    .catch(error => {
                        const payload = error.response.data;
                        if (payload.message) {
                            this.$msg(payload.message);
                        }
                        if (payload.errors) {
                            this.errors = payload.errors;
                        }
                    });
            }
        }
    }
</script>