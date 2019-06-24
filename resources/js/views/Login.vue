<template>
    <div>
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid logged-out-page">

                <form class="mui-form">
                    
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="email" v-model="email">
                         <label>Email</label>
                    </div>
                    <div class="mui-textfield mui-textfield--float-label">
                        <input type="password" v-model="password">
                         <label>Password</label>
                    </div>
                    <button type="button" class="mui-btn login-btn" v-on:click="attempt">Log In</button>
                    <div>
                        <button type="button" class="mui-btn social-btn facebook" v-on:click="facebookLogin">
                            Log in with Facebook
                        </button>
                        <button type="button" class="mui-btn social-btn google" v-on:click="googleLogin">
                            Log in with Google
                        </button>
                    </div>
                    <div class='forgot_password'>Forgot Password?</div>

                    <div v-if="type === 'account'">
                        <div class='divider'>
                            <span></span><b>OR</b>
                        </div>
                        <button type="button" class="mui-btn register-btn" v-on:click="register">
                            Register a Rounds Account
                        </button>
                    </div>

                </form>
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
            this.type = this.$route.name === 'login.member' ? 'member' : 'account';
            const user = storage.get('user');

            if (user && user.type) {
                this.login(user);
            } else {
                this.attemptExternal();
            }
        },
        methods: {
            attemptExternal() {
                this.error = null;
                this.loading = true;

                const query = this.$route.query;
                if (typeof query === 'object' && query.provider && query.token) {
                    axios
                        .post(`/api/${this.type}s/login`, {
                            category_id: 1,
                            email: this.email,
                            provider: query.provider,
                            token: query.token,
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
                    window.history.replaceState({}, document.title, window.location.href.split("?")[0]);
                }
            },
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
            facebookLogin() {
                window.location = `/login/external/facebook/${this.type}`;
            },
            googleLogin() {
                window.location = `/login/external/google/${this.type}`;
            },
            register() {
                this.$router.push({name: "register"});
            }
        }
    }
</script>