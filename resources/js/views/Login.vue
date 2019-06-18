<template>
    <div>
        <div class="mui--appbar-height"></div>
        <div class='login_banner'><img src='../../images/login-icon.svg' class='loginlogo'></div>
        <div class="mui-container-fluid login_screen">

            <div class="mui-panel welcome_form">
                <form class="mui-form">
                    
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="email" v-model="email" placeholder='Email'>
                        <!-- <label>Email</label> -->
                    </div>
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="password" v-model="password" placeholder='Password'>
                        <!-- <label>Password</label> -->
                    </div>
                    <div class="mui-select">
                        <select v-model="type">
                            <option value="account">Account Holder</option>
                            <option value="member">Member</option>
                        </select>
                    </div>
                    <button type="button" class="mui-btn login_btn" v-on:click="attempt">Log In</button>
                    <div>
                        <button type="button" class="mui-btn social_btn facebook">Log in with Facebook</button>
                        <button type="button" class="mui-btn social_btn google">Log in with Google</button>
                    </div>
                    <div class='forgot_password'>Forgot Password?</div>
                    <div class='divider'><span></span><b>OR</b></div>
                    <button type="button" class="mui-btn reg_btn" v-on:click="register">Register a Rounds Account</button>
                </form>
            </div>
            
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import storage from '../helpers/storage';
    import Api from '../helpers/api';
    import Events from '../helpers/events';
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
            const user = storage.get('user');
            if (user && user.type) {
                this.type = user.type;
                this.login(user);
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
                        const payload = error.response.data;
                        if (payload.message) {
                            this.$msg(payload.message);
                        }
                    });
            },
            login(user) {
                if (user != null && typeof user === 'object') {
                    storage.set('user', user);
                    Api.init(axios, storage, this);
                    Events.dispatch('account-changed', { user });

                    if (this.type === 'account') {
                        Api.updateMembers(axios, storage)
                            .then(members => {
                                Events.dispatch('members-changed', { members });
                                this.$router.push({name: "schedule"});
                                this.$msg('Login successful');
                            })
                            .catch(error => {
                                if (error.message) {
                                    this.$msg(error.message);
                                }
                            });
                    }
                    else if (this.type === 'member') {
                        this.$router.push({name: "schedule"});
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