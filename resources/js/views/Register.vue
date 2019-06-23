<template>
    <div>
        <div class="mui--appbar-height"></div>
        <div class='login_banner'><img src='../../images/login-icon.svg' class='loginlogo'></div>
        <div class="mui-container-fluid login_screen">

            <div class="mui-panel welcome_form">
                <form class="mui-form">

                    <div class="mui-textfield">
                        <input type="text" v-model="name">
                        <label>Name</label>
                    </div>
                    <div class="mui-textfield">
                        <input type="email" v-model="email">
                        <label>Email</label>
                    </div>
                    <div class="mui-textfield">
                        <input type="number" v-model="phone">
                        <label>Phone</label>
                    </div>
                    <div class="mui-textfield">
                        <input type="password" v-model="password">
                        <label>Password</label>
                    </div>
                    <div class="mui-textfield">
                        <input type="password" v-model="repeat_password">
                        <label>Repeat Password</label>
                    </div>
                    <button type="button" class="mui-btn login_btn" v-on:click="register">
                        Register
                    </button>
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
                name: '',
                email: '',
                phone: '',
                password: '',
                repeat_password: '',
                type: 'account'
            };
        },
        methods: {
            register() {
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

                        this.$router.push({name: "login"});
                        this.$msg("Account created successfully");
                    })
                    .catch(error => {
                        const payload = error.response.data;
                        if (payload.message) {
                            this.$msg(payload.message);
                        }
                        if (payload.errors) {
                            this.$msg(JSON.stringify(payload.errors));
                        }
                    });
            }
        }
    }
</script>