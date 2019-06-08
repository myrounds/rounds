<template>
    <div class="members">
        <div class="mui--appbar-height"></div>
        <div class="mui-container-fluid">

            <button class="add_assignee" @click="createMember"><i class="fas fa-user-plus"></i></button>

            <div class="mui-panel">
                <div class="row header">
                    <div class="col">Name</div>
                    <div class="col">Email</div>
                    <div class="col">Phone</div>
                    <div class="col">License Plate</div>
                </div>
                <div class="row" v-for="member in members" @click="selectMember">
                    <div class="col" :id="member.id">{{member.name}}</div>
                    <div class="col" :id="member.id">{{member.email}}</div>
                    <div class="col" :id="member.id">{{member.phone}}</div>
                    <div class="col" :id="member.id">{{member.license_plate}}</div>
                </div>
            </div>

        </div>

        <modal v-if="showManageMember" @close="showManageMember = false">
            <h3 slot="header">
                {{selected.name || 'New Member'}}
            </h3>
            <div slot="body">

                <div class="mui-textfield mui-textfield--float-label">
                    <input type="text" :value="selected.name">
                    <label>Name</label>
                </div>

                <div class="mui-textfield mui-textfield--float-label">
                    <input type="email" :value="selected.email">
                    <label>Email</label>
                </div>

                <div class="mui-textfield mui-textfield--float-label">
                    <input type="text" :value="selected.phone">
                    <label>Phone</label>
                </div>

                <div class="mui-textfield mui-textfield--float-label">
                    <input type="text" :value="selected.license_plate">
                    <label>License Plate</label>
                </div>

                <div class="mui-textfield">
                    <textarea>{{selected.notes}}</textarea>
                    <label>Notes</label>
                </div>

                <button class="mui-btn mui-btn--raised">Save</button>

                <hr>

                <button class="mui-btn mui-btn--raised">Set Password</button>

            </div>
        </modal>

    </div>
</template>

<script>
    import Storage from '../helpers/storage';
    import Modal from '../components/Modal';
    export default {
        components: {Modal},
        data() {
            return {
                loading: false,
                members: [],
                selected: {},
                showManageMember: false
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
            createMember() {
                this.selected = {};
                this.showManageMember = true;
            }
        }
    }
</script>