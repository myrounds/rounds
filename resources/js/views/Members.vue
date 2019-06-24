<template>
    <div class="members">
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid">

            <button class="add-member" @click="showCreateMember"><i class="fas fa-user-plus"></i></button>

            <div class="mui-panel">
                <div class="row header">
                    <div class="col">Name</div>
                    <div class="col">Email</div>
                    <div class="col">Phone</div>
                    <div class="col">License Plate</div>
                    <div class="col">Status</div>
                </div>
                <div class="row" v-for="member in members" @click="selectMember">
                    <div class="col" :id="member.id">{{member.name || '-'}}</div>
                    <div class="col" :id="member.id">{{member.email || '-'}}</div>
                    <div class="col" :id="member.id">{{member.phone || '-'}}</div>
                    <div class="col" :id="member.id">{{member.license_plate || '-'}}</div>
                    <div class="col" :id="member.id">Active</div>
                </div>
            </div>

        </div>

        <modal v-if="showManageMember" @close="showManageMember = false">
            <h3 slot="header">
                {{selected.name || 'New Member'}}
            </h3>
            <div slot="body">

                <form class="mui-form">
                    <div class="mui-textfield">
                        <input type="text" v-model="selected.name">
                        <label>Name</label>
                        <div class="error" v-for="error in this.errors.name">{{error}}</div>
                    </div>

                    <div class="mui-textfield">
                        <input type="email" v-model="selected.email">
                        <label>Email</label>
                        <div class="error" v-for="error in this.errors.email">{{error}}</div>
                    </div>

                    <div class="mui-textfield">
                        <input type="text" v-model="selected.phone">
                        <label>Phone</label>
                        <div class="error" v-for="error in this.errors.phone">{{error}}</div>
                    </div>

                    <div class="mui-textfield">
                        <input type="text" v-model="selected.license_plate">
                        <label>License Plate</label>
                        <div class="error" v-for="error in this.errors.license_plate">{{error}}</div>
                    </div>

                    <div class="mui-textfield">
                        <textarea v-model="selected.notes"></textarea>
                        <label>Notes</label>
                        <div class="error" v-for="error in this.errors.notes">{{error}}</div>
                    </div>

                    <button type="button" class="mui-btn mui-btn--raised" @click="saveMember">Save</button>

                    <hr>

                    <button type="button" class="mui-btn mui-btn--raised">Reset Password</button>
                </form>

            </div>
        </modal>

    </div>
</template>

<script>
    import axios from 'axios';
    import Storage from '../helpers/storage';
    import Api from '../helpers/api';
    import Events from '../helpers/events';
    import Modal from '../components/Modal';
    export default {
        components: {Modal},
        data() {
            return {
                loading: false,
                members: [],
                selected: {},
                showManageMember: false,
                name: null,
                errors: {}
            };
        },
        created() {
            this.members = Storage.get('members');
            this.error = this.users = null;
            this.loading = true;
        },
        methods: {
            selectMember(event) {
                const id = event.target.id;

                this.selected = this.members.find(member => parseInt(member.id) === parseInt(id));
                this.showManageMember = true;
            },
            showCreateMember() {
                this.selected = {};
                this.showManageMember = true;
            },

            saveMember() {
                Object.keys(this.selected).forEach((key) => (this.selected [key] == null) && delete this.selected [key]); // todo: use Object.clean();

                axios[this.selected.id ? 'put' : 'post'](`/api/members/${this.selected.id ? `update/${this.selected.id}` : 'create'}`, this.selected)
                    .then(response => {
                        const payload = response.data;
                        const member = payload.data;

                        this.$msg(`Member ${this.selected.id ? 'updated' : 'added'} successfully`);
                        this.showManageMember = false;
                        this.selected = {};

                        Api.updateMembers(axios, Storage)
                            .then(members => {
                                Events.dispatch('members-changed', { members });
                            })
                            .catch(error => {
                                if (error.message) {
                                    this.$msg(error.message);
                                }
                            });
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